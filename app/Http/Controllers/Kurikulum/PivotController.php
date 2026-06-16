<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Jobs\SyncMkCplJob;
use App\Models\BahanKajian;
use App\Models\CplProdi;
use App\Models\CplSndikti;
use App\Models\Kurikulum;
use App\Models\MataKuliah;
use App\Models\Pl;
use App\Services\ExcelExportService;
use App\Services\MatrixConsistencyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PivotController extends Controller
{
    public function __construct(private MatrixConsistencyService $consistency)
    {
    }

    /**
     * Generic toggle endpoint untuk semua pivot matrix.
     * Body: { table, keys: {col1: id1, col2: id2, ...}, checked: bool }
     * Validasi: pasangan id harus benar-benar milik kurikulum ini.
     */
    public function toggle(Request $request, Kurikulum $kurikulum): JsonResponse
    {
        if ($kurikulum->isArsip()) {
            return response()->json(['ok' => false, 'message' => 'Kurikulum sudah diarsipkan.'], 403);
        }

        $data = $request->validate([
            // pivot_cpl_bk_mk dihapus dari sini — matriks ini auto-derived, tidak boleh diedit manual
            'table'   => 'required|string|in:pivot_pl_cpl,pivot_cplsn_cplp,pivot_cpl_bk,pivot_mk_bk,pivot_mk_cpl,pivot_cpl_cpmk',
            'keys'    => 'required|array',
            'keys.*'  => 'required|integer|min:1',
            'checked' => 'required|boolean',
        ]);

        // Validasi ownership: setiap id harus milik kurikulum aktif
        if (!$this->validateKeysBelongToKurikulum($kurikulum, $data['table'], $data['keys'])) {
            return response()->json(['ok' => false, 'message' => 'Data tidak valid untuk kurikulum ini.'], 422);
        }

        $derived       = false;
        $cplProdiToSync = []; // id CPL Prodi yang referensinya perlu disinkronkan
        DB::transaction(function () use ($data, &$derived, &$cplProdiToSync, $kurikulum) {
            // Special handling untuk pivot_mk_cpl (composite key butuh id_cpmk).
            // Frontend hanya kirim (id_mk, id_cpl); kita lookup/auto-create CPMK.
            if ($data['table'] === 'pivot_mk_cpl' && isset($data['keys']['id_mk'], $data['keys']['id_cpl']) && !isset($data['keys']['id_cpmk'])) {
                $this->handleMkCplToggle($kurikulum, $data['keys']['id_mk'], $data['keys']['id_cpl'], $data['checked']);
                $derived = true;
                return;
            }

            $q = DB::table($data['table']);
            foreach ($data['keys'] as $col => $val) {
                $q->where($col, $val);
            }

            if ($data['checked']) {
                // INSERT IGNORE pattern: cek dulu, baru insert
                if (!$q->exists()) {
                    DB::table($data['table'])->insert($data['keys'] + $this->defaultExtras($data['table']));
                }

                // Untuk pivot_cpl_bk_mk (matriks 3D): pastikan primer CPL↔BK dan MK↔BK juga ada
                // agar relasi tidak menggantung. Ini bikin matriks 3D bisa di-edit langsung
                // dan otomatis menulis ke matriks primer. Setelah menulis primer, dispatch job
                // asinkron untuk sync pivot_mk_cpl agar tidak memblokir response.
                if ($data['table'] === 'pivot_cpl_bk_mk') {
                    $k = $data['keys'];
                    DB::table('pivot_cpl_bk')->updateOrInsert(
                        ['id_cpl' => $k['id_cpl'], 'id_bk' => $k['id_bk']], []
                    );
                    DB::table('pivot_mk_bk')->updateOrInsert(
                        ['id_mk'  => $k['id_mk'],  'id_bk' => $k['id_bk']], []
                    );
                    $derived = true;
                }
            } else {
                $q->delete();

                // Cascade dari primer → derived: kalau user uncheck CPL↔BK atau MK↔BK,
                // hapus baris pivot_cpl_bk_mk yang bersangkutan supaya tidak menggantung.
                if ($data['table'] === 'pivot_cpl_bk') {
                    DB::table('pivot_cpl_bk_mk')
                        ->where('id_cpl', $data['keys']['id_cpl'])
                        ->where('id_bk',  $data['keys']['id_bk'])
                        ->delete();
                } elseif ($data['table'] === 'pivot_mk_bk') {
                    DB::table('pivot_cpl_bk_mk')
                        ->where('id_mk', $data['keys']['id_mk'])
                        ->where('id_bk', $data['keys']['id_bk'])
                        ->delete();
                } elseif ($data['table'] === 'pivot_cpl_bk_mk') {
                    // Saat baris 3D dihapus manual, dispatch job sync mk_cpl.
                    $derived = true;
                }
            }

            // Saat user men-toggle matriks primer (CPL↔BK / MK↔BK):
            //   - syncCplBkMk dijalankan synchronous (additive, cepat)
            //   - syncMkCpl akan didispatch sebagai background job setelah transaksi selesai
            if (in_array($data['table'], ['pivot_cpl_bk', 'pivot_mk_bk'], true)) {
                if ($data['checked']) {
                    // Additive: tambah row turunan baru yang belum ada (sync, ringan)
                    $this->consistency->syncCplBkMk($kurikulum, additive: true);
                }
                $derived = true;
            }

            // Tandai CPL Prodi yang perlu disinkronkan referensinya
            if ($data['table'] === 'pivot_cplsn_cplp' && isset($data['keys']['id_cpl_prodi'])) {
                $cplProdiToSync[] = (int) $data['keys']['id_cpl_prodi'];
            }
        });

        // Dispatch SETELAH transaksi berhasil commit — job tidak perlu dirollback
        // jika transaksi gagal karena belum pernah dikirim.
        if ($derived) {
            SyncMkCplJob::dispatch($kurikulum->id);
        }

        // Sinkronkan referensi CPL Prodi dari pemetaan CPL SN-Dikti
        if (!empty($cplProdiToSync)) {
            $this->syncCplProdiReferensi($cplProdiToSync);
        }

        return response()->json([
            'ok'      => true,
            'derived' => $derived,
            'message' => $derived
                ? 'Tersimpan & matriks turunan disinkronkan.'
                : 'Tersimpan.',
        ]);
    }

    /**
     * Special handler untuk toggle pivot_mk_cpl.
     *
     * On check: cari CPMK yang sudah ada untuk (mk, cpl). Jika tidak ada, auto-create
     *           placeholder CPMK + Sub-CPMK standar, lalu insert pivot_mk_cpl.
     * On uncheck: hapus SEMUA baris pivot_mk_cpl untuk (mk, cpl) terlepas dari CPMK.
     */
    private function handleMkCplToggle(Kurikulum $kurikulum, int $mkId, int $cplId, bool $checked): void
    {
        if (!$checked) {
            DB::table('pivot_mk_cpl')
                ->where('id_mk', $mkId)
                ->where('id_cpl', $cplId)
                ->delete();
            return;
        }

        $cpmk = \App\Models\Cpmk::where('id_mk', $mkId)->where('id_cpl', $cplId)->first();

        if (!$cpmk) {
            $mk  = \App\Models\MataKuliah::find($mkId);
            $cpl = \App\Models\CplProdi::find($cplId);
            $seq = \App\Models\Cpmk::where('id_mk', $mkId)->count() + 1;

            $cpmk = \App\Models\Cpmk::create([
                'id_kurikulum' => $kurikulum->id,
                'id_mk'        => $mkId,
                'id_cpl'       => $cplId,
                'kode_cpmk'    => sprintf('CPMK-%s-%d', $mk->kode_mk, $seq),
                'deskripsi'    => sprintf('Mahasiswa mampu memahami dan menerapkan konsep %s yang terkait dengan capaian %s.', $mk->nama_mk, $cpl->kode_cpl),
                'urutan'       => $seq,
            ]);

            // Sub-CPMK default 2 buah
            foreach ([
                [1, sprintf('Memahami konsep dasar %s.', $mk->nama_mk), 50.00],
                [2, sprintf('Menerapkan %s dalam studi kasus.', $mk->nama_mk), 50.00],
            ] as [$urut, $desc, $bobot]) {
                \App\Models\SubCpmk::create([
                    'id_cpmk'      => $cpmk->id,
                    'kode_sub_cpmk'=> sprintf('SUB-%s-%d.%d', $mk->kode_mk, $seq, $urut),
                    'deskripsi'    => $desc,
                    'bobot'        => $bobot,
                    'urutan'       => $urut,
                ]);
            }

            // Pivot CPL ↔ CPMK juga di-isi otomatis
            DB::table('pivot_cpl_cpmk')->updateOrInsert(
                ['id_cpl' => $cplId, 'id_cpmk' => $cpmk->id],
                ['bobot' => 1.00]
            );
        }

        DB::table('pivot_mk_cpl')->updateOrInsert(
            ['id_mk' => $mkId, 'id_cpl' => $cplId, 'id_cpmk' => $cpmk->id],
            ['bobot' => 1.00]
        );
    }

    private function defaultExtras(string $table): array
    {
        return match ($table) {
            'pivot_mk_cpl'   => ['bobot' => 1.00, 'id_cpmk' => 0], // butuh CPMK eksplisit; ini fallback
            'pivot_cpl_cpmk' => ['bobot' => 1.00],
            default          => [],
        };
    }

    private function validateKeysBelongToKurikulum(Kurikulum $k, string $table, array $keys): bool
    {
        $check = function (string $model, ?int $id) use ($k): bool {
            if ($id === null) return true;
            return match ($model) {
                'pl'        => $k->pl()->whereKey($id)->exists(),
                'cpl_prodi' => $k->cplProdi()->whereKey($id)->exists(),
                'bk'        => $k->bahanKajian()->whereKey($id)->exists(),
                'mk'        => $k->mataKuliah()->whereKey($id)->exists(),
                'cpmk'      => \App\Models\Cpmk::where('id_kurikulum', $k->id)->whereKey($id)->exists(),
                'cplsn'     => CplSndikti::whereKey($id)->exists(),
                default     => false,
            };
        };

        return match ($table) {
            'pivot_pl_cpl'     => $check('pl', $keys['id_pl'] ?? null) && $check('cpl_prodi', $keys['id_cpl'] ?? null),
            'pivot_cplsn_cplp' => $check('cplsn', $keys['id_cpl_sndikti'] ?? null) && $check('cpl_prodi', $keys['id_cpl_prodi'] ?? null),
            'pivot_cpl_bk'     => $check('cpl_prodi', $keys['id_cpl'] ?? null) && $check('bk', $keys['id_bk'] ?? null),
            'pivot_mk_bk'      => $check('mk', $keys['id_mk'] ?? null) && $check('bk', $keys['id_bk'] ?? null),
            // pivot_mk_cpl: id_cpmk opsional (auto-link/create). Cukup validasi mk + cpl.
            'pivot_mk_cpl'     => $check('mk', $keys['id_mk'] ?? null) && $check('cpl_prodi', $keys['id_cpl'] ?? null),
            'pivot_cpl_bk_mk'  => $check('cpl_prodi', $keys['id_cpl'] ?? null) && $check('bk', $keys['id_bk'] ?? null) && $check('mk', $keys['id_mk'] ?? null),
            'pivot_cpl_cpmk'   => $check('cpl_prodi', $keys['id_cpl'] ?? null) && $check('cpmk', $keys['id_cpmk'] ?? null),
            default            => false,
        };
    }

    /**
     * Bangun struktur sheet (headerRows + rows + colWidths) untuk matriks checkbox
     * sederhana (baris × kolom, isi = "✓"/"") yang dipakai oleh export Phase 2.
     *
     * @param Collection $rowList        Baris matriks (model dengan properti 'id')
     * @param Collection $colList        Kolom matriks (model dengan properti 'id')
     * @param Collection $existing       [rowId => array kolomId yang tercentang]
     * @param \Closure   $rowLabel       fn($row) => string label baris
     * @param \Closure   $colLabel       fn($col) => string label kolom
     * @param string     $cornerLabel    Label sel pojok kiri atas
     * @param string     $bg             Warna background header (hex tanpa #)
     * @param array      $extraRowHeaders Label kolom tambahan setelah label baris (opsional)
     * @param \Closure|null $extraRowValues fn($row) => array nilai untuk $extraRowHeaders
     */
    private function buildMatrixSheet(
        Collection $rowList,
        Collection $colList,
        Collection $existing,
        \Closure $rowLabel,
        \Closure $colLabel,
        string $cornerLabel,
        string $bg,
        array $extraRowHeaders = [],
        ?\Closure $extraRowValues = null,
    ): array {
        $headerRow = [['label' => $cornerLabel, 'bg' => $bg]];
        foreach ($extraRowHeaders as $extraHeader) {
            $headerRow[] = ['label' => $extraHeader, 'bg' => $bg];
        }
        foreach ($colList as $col) {
            $headerRow[] = ['label' => $colLabel($col), 'bg' => $bg];
        }

        $rows = [];
        foreach ($rowList as $row) {
            $line = [$rowLabel($row)];
            if ($extraRowValues) {
                foreach ($extraRowValues($row) as $value) {
                    $line[] = $value;
                }
            }
            foreach ($colList as $col) {
                $checked = in_array($col->id, $existing[$row->id] ?? []);
                $line[] = $checked ? '✓' : '';
            }
            $rows[] = $line;
        }

        $colWidths = [20];
        foreach ($extraRowHeaders as $extraHeader) {
            $colWidths[] = 18;
        }
        foreach ($colList as $col) {
            $colWidths[] = 10;
        }

        return [
            'headerRows' => [$headerRow],
            'rows'       => $rows,
            'colWidths'  => $colWidths,
        ];
    }

    // ── PL ↔ CPL Prodi ──────────────────────────────────────────────────────────

    public function plCpl(Kurikulum $kurikulum)
    {
        $plList  = $kurikulum->pl()->orderBy('urutan')->get();
        $cplList = $kurikulum->cplProdi()->orderBy('urutan')->get();

        // Ambil data pivot yang sudah ada
        $existing = DB::table('pivot_pl_cpl')
            ->whereIn('id_pl', $plList->pluck('id'))
            ->get()
            ->groupBy('id_pl')
            ->map(fn ($rows) => $rows->pluck('id_cpl')->toArray());

        return view('kurikulum.pivot.pl-cpl', compact('kurikulum', 'plList', 'cplList', 'existing'));
    }

    public function savePlCpl(Request $request, Kurikulum $kurikulum)
    {
        $plList = $kurikulum->pl()->pluck('id')->toArray();

        DB::transaction(function () use ($request, $plList) {
            // Hapus semua entri lama
            DB::table('pivot_pl_cpl')->whereIn('id_pl', $plList)->delete();

            $inserts = [];
            foreach ($request->input('pivot', []) as $plId => $cplIds) {
                if (!in_array($plId, $plList)) {
                    continue;
                }
                foreach (array_keys($cplIds) as $cplId) {
                    $inserts[] = ['id_pl' => $plId, 'id_cpl' => $cplId];
                }
            }

            if (!empty($inserts)) {
                DB::table('pivot_pl_cpl')->insert($inserts);
            }
        });

        return back()->with('success', 'Matriks PL ↔ CPL berhasil disimpan.');
    }

    public function exportPlCpl(Kurikulum $kurikulum, ExcelExportService $excel)
    {
        $plList  = $kurikulum->pl()->orderBy('urutan')->get();
        $cplList = $kurikulum->cplProdi()->orderBy('urutan')->get();

        $existing = DB::table('pivot_pl_cpl')
            ->whereIn('id_pl', $plList->pluck('id'))
            ->get()
            ->groupBy('id_pl')
            ->map(fn ($rows) => $rows->pluck('id_cpl')->toArray());

        $sheet = $this->buildMatrixSheet(
            $plList, $cplList, $existing,
            fn ($pl) => $pl->kode_pl,
            fn ($cpl) => $cpl->kode_cpl,
            'PL \\ CPL Prodi',
            'F59E0B',
        );

        return $excel->download("matriks-pl-cpl-{$kurikulum->kode}.xlsx", [
            'PL-CPL' => $sheet,
        ]);
    }

    // ── CPL SN-Dikti ↔ CPL Prodi ───────────────────────────────────────────────

    /**
     * Setelah pivot_cplsn_cplp berubah, sinkronkan kolom `referensi` pada CPL Prodi
     * agar terisi otomatis dengan kode CPL SN-Dikti yang terpetakan (dipisah koma).
     */
    private function syncCplProdiReferensi(array $cplProdiIds): void
    {
        if (empty($cplProdiIds)) {
            return;
        }

        // Ambil semua kode SN-Dikti yang dipetakan ke masing-masing CPL Prodi
        $mappings = DB::table('pivot_cplsn_cplp')
            ->join('cpl_sndikti', 'pivot_cplsn_cplp.id_cpl_sndikti', '=', 'cpl_sndikti.id')
            ->whereIn('pivot_cplsn_cplp.id_cpl_prodi', $cplProdiIds)
            ->select('pivot_cplsn_cplp.id_cpl_prodi', 'cpl_sndikti.kode', 'cpl_sndikti.urutan')
            ->orderBy('cpl_sndikti.urutan')
            ->get()
            ->groupBy('id_cpl_prodi');

        foreach ($cplProdiIds as $id) {
            $kodes = ($mappings->get($id) ?? collect())->pluck('kode')->implode(', ');
            CplProdi::where('id', $id)->update(['referensi' => $kodes ?: null]);
        }
    }

    public function cplsnCplp(Kurikulum $kurikulum)
    {
        $cplsnList = CplSndikti::orderByRaw("FIELD(kategori, 'Sikap', 'Keterampilan Umum', 'Keterampilan Khusus', 'Pengetahuan')")
            ->orderBy('urutan')
            ->get();
        $cplpList  = $kurikulum->cplProdi()->orderBy('urutan')->get();

        $existing = DB::table('pivot_cplsn_cplp')
            ->whereIn('id_cpl_prodi', $cplpList->pluck('id'))
            ->get()
            ->groupBy('id_cpl_sndikti')
            ->map(fn ($rows) => $rows->pluck('id_cpl_prodi')->toArray());

        return view('kurikulum.pivot.cplsn-cplp', compact('kurikulum', 'cplsnList', 'cplpList', 'existing'));
    }

    public function saveCplsnCplp(Request $request, Kurikulum $kurikulum)
    {
        $cplpIds = $kurikulum->cplProdi()->pluck('id')->toArray();

        DB::transaction(function () use ($request, $cplpIds) {
            DB::table('pivot_cplsn_cplp')->whereIn('id_cpl_prodi', $cplpIds)->delete();

            $inserts = [];
            foreach ($request->input('pivot', []) as $cplsnId => $cplpChecked) {
                foreach (array_keys($cplpChecked) as $cplpId) {
                    if (!in_array($cplpId, $cplpIds)) {
                        continue;
                    }
                    $inserts[] = ['id_cpl_sndikti' => $cplsnId, 'id_cpl_prodi' => $cplpId];
                }
            }

            if (!empty($inserts)) {
                DB::table('pivot_cplsn_cplp')->insert($inserts);
            }
        });

        // Sinkronkan referensi untuk semua CPL Prodi yang terpengaruh
        $this->syncCplProdiReferensi($cplpIds);

        return back()->with('success', 'Matriks CPL SN-Dikti ↔ CPL Prodi berhasil disimpan.');
    }

    public function exportCplsnCplp(Kurikulum $kurikulum, ExcelExportService $excel)
    {
        $cplsnList = CplSndikti::orderByRaw("FIELD(kategori, 'Sikap', 'Keterampilan Umum', 'Keterampilan Khusus', 'Pengetahuan')")
            ->orderBy('urutan')
            ->get();
        $cplpList  = $kurikulum->cplProdi()->orderBy('urutan')->get();

        $existing = DB::table('pivot_cplsn_cplp')
            ->whereIn('id_cpl_prodi', $cplpList->pluck('id'))
            ->get()
            ->groupBy('id_cpl_sndikti')
            ->map(fn ($rows) => $rows->pluck('id_cpl_prodi')->toArray());

        $sheet = $this->buildMatrixSheet(
            $cplsnList, $cplpList, $existing,
            fn ($cplsn) => $cplsn->kode,
            fn ($cplp) => $cplp->kode_cpl,
            'CPL SN-Dikti \\ CPL Prodi',
            'F59E0B',
            ['Kategori'],
            fn ($cplsn) => [$cplsn->kategori],
        );

        return $excel->download("matriks-cplsn-cplp-{$kurikulum->kode}.xlsx", [
            'CPLSN-CPLP' => $sheet,
        ]);
    }

    // ── CPL Prodi ↔ Bahan Kajian ────────────────────────────────────────────────

    public function cplBk(Kurikulum $kurikulum)
    {
        $cplList = $kurikulum->cplProdi()->orderBy('urutan')->get();
        $bkList  = $kurikulum->bahanKajian()->orderBy('urutan')->get();

        $existing = DB::table('pivot_cpl_bk')
            ->whereIn('id_cpl', $cplList->pluck('id'))
            ->get()
            ->groupBy('id_cpl')
            ->map(fn ($rows) => $rows->pluck('id_bk')->toArray());

        return view('kurikulum.pivot.cpl-bk', compact('kurikulum', 'cplList', 'bkList', 'existing'));
    }

    public function saveCplBk(Request $request, Kurikulum $kurikulum)
    {
        $cplIds = $kurikulum->cplProdi()->pluck('id')->toArray();

        DB::transaction(function () use ($request, $cplIds) {
            DB::table('pivot_cpl_bk')->whereIn('id_cpl', $cplIds)->delete();

            $inserts = [];
            foreach ($request->input('pivot', []) as $cplId => $bkChecked) {
                if (!in_array($cplId, $cplIds)) {
                    continue;
                }
                foreach (array_keys($bkChecked) as $bkId) {
                    $inserts[] = ['id_cpl' => $cplId, 'id_bk' => $bkId];
                }
            }

            if (!empty($inserts)) {
                DB::table('pivot_cpl_bk')->insert($inserts);
            }
        });

        return back()->with('success', 'Matriks CPL ↔ Bahan Kajian berhasil disimpan.');
    }

    public function exportCplBk(Kurikulum $kurikulum, ExcelExportService $excel)
    {
        $cplList = $kurikulum->cplProdi()->orderBy('urutan')->get();
        $bkList  = $kurikulum->bahanKajian()->orderBy('urutan')->get();

        $existing = DB::table('pivot_cpl_bk')
            ->whereIn('id_cpl', $cplList->pluck('id'))
            ->get()
            ->groupBy('id_cpl')
            ->map(fn ($rows) => $rows->pluck('id_bk')->toArray());

        $sheet = $this->buildMatrixSheet(
            $cplList, $bkList, $existing,
            fn ($cpl) => $cpl->kode_cpl,
            fn ($bk) => $bk->kode_bk,
            'CPL Prodi \\ Bahan Kajian',
            'F59E0B',
        );

        return $excel->download("matriks-cpl-bk-{$kurikulum->kode}.xlsx", [
            'CPL-BK' => $sheet,
        ]);
    }

    // ── MK ↔ Bahan Kajian ───────────────────────────────────────────────────────

    public function mkBk(Kurikulum $kurikulum)
    {
        $mkList = $kurikulum->mataKuliah()->orderBy('semester')->orderBy('kode_mk')->get();
        $bkList = $kurikulum->bahanKajian()->orderBy('urutan')->get();

        $existing = DB::table('pivot_mk_bk')
            ->whereIn('id_mk', $mkList->pluck('id'))
            ->get()
            ->groupBy('id_mk')
            ->map(fn ($rows) => $rows->pluck('id_bk')->toArray());

        return view('kurikulum.pivot.mk-bk', compact('kurikulum', 'mkList', 'bkList', 'existing'));
    }

    public function saveMkBk(Request $request, Kurikulum $kurikulum)
    {
        $mkIds = $kurikulum->mataKuliah()->pluck('id')->toArray();

        DB::transaction(function () use ($request, $mkIds) {
            DB::table('pivot_mk_bk')->whereIn('id_mk', $mkIds)->delete();

            $inserts = [];
            foreach ($request->input('pivot', []) as $mkId => $bkChecked) {
                if (!in_array($mkId, $mkIds)) {
                    continue;
                }
                foreach (array_keys($bkChecked) as $bkId) {
                    $inserts[] = ['id_mk' => $mkId, 'id_bk' => $bkId];
                }
            }

            if (!empty($inserts)) {
                DB::table('pivot_mk_bk')->insert($inserts);
            }
        });

        return back()->with('success', 'Matriks MK ↔ Bahan Kajian berhasil disimpan.');
    }

    public function exportMkBk(Kurikulum $kurikulum, ExcelExportService $excel)
    {
        $mkList = $kurikulum->mataKuliah()->orderBy('semester')->orderBy('kode_mk')->get();
        $bkList = $kurikulum->bahanKajian()->orderBy('urutan')->get();

        $existing = DB::table('pivot_mk_bk')
            ->whereIn('id_mk', $mkList->pluck('id'))
            ->get()
            ->groupBy('id_mk')
            ->map(fn ($rows) => $rows->pluck('id_bk')->toArray());

        $sheet = $this->buildMatrixSheet(
            $mkList, $bkList, $existing,
            fn ($mk) => $mk->kode_mk,
            fn ($bk) => $bk->kode_bk,
            'MK \\ Bahan Kajian',
            'F59E0B',
        );

        return $excel->download("matriks-mk-bk-{$kurikulum->kode}.xlsx", [
            'MK-BK' => $sheet,
        ]);
    }

    // ── MK ↔ CPL (via CPMK) ─────────────────────────────────────────────────────

    public function mkCpl(Kurikulum $kurikulum)
    {
        $mkList  = $kurikulum->mataKuliah()->orderBy('semester')->orderBy('kode_mk')->get();
        $cplList = $kurikulum->cplProdi()->orderBy('urutan')->get();

        $existing = DB::table('pivot_mk_cpl')
            ->whereIn('id_mk', $mkList->pluck('id'))
            ->get()
            ->groupBy('id_mk')
            ->map(fn ($rows) => $rows->pluck('id_cpl')->toArray());

        return view('kurikulum.pivot.mk-cpl', compact('kurikulum', 'mkList', 'cplList', 'existing'));
    }

    public function saveMkCpl(Request $request, Kurikulum $kurikulum)
    {
        $mkIds = $kurikulum->mataKuliah()->pluck('id')->toArray();

        DB::transaction(function () use ($request, $mkIds) {
            DB::table('pivot_mk_cpl')->whereIn('id_mk', $mkIds)->delete();

            $inserts = [];
            foreach ($request->input('pivot', []) as $mkId => $cplChecked) {
                if (!in_array($mkId, $mkIds)) {
                    continue;
                }
                foreach (array_keys($cplChecked) as $cplId) {
                    $inserts[] = ['id_mk' => $mkId, 'id_cpl' => $cplId];
                }
            }

            if (!empty($inserts)) {
                DB::table('pivot_mk_cpl')->insert($inserts);
            }
        });

        return back()->with('success', 'Matriks MK ↔ CPL berhasil disimpan.');
    }

    public function exportMkCpl(Kurikulum $kurikulum, ExcelExportService $excel)
    {
        $mkList  = $kurikulum->mataKuliah()->orderBy('semester')->orderBy('kode_mk')->get();
        $cplList = $kurikulum->cplProdi()->orderBy('urutan')->get();

        $existing = DB::table('pivot_mk_cpl')
            ->whereIn('id_mk', $mkList->pluck('id'))
            ->get()
            ->groupBy('id_mk')
            ->map(fn ($rows) => $rows->pluck('id_cpl')->toArray());

        $sheet = $this->buildMatrixSheet(
            $mkList, $cplList, $existing,
            fn ($mk) => $mk->kode_mk,
            fn ($cpl) => $cpl->kode_cpl,
            'MK \\ CPL Prodi',
            'F59E0B',
        );

        return $excel->download("matriks-mk-cpl-{$kurikulum->kode}.xlsx", [
            'MK-CPL' => $sheet,
        ]);
    }

    // ── CPL Prodi ↔ CPMK ───────────────────────────────────────────────────────

    public function cplCpmk(Kurikulum $kurikulum)
    {
        $cplList = $kurikulum->cplProdi()->orderBy('urutan')->get();

        // Ambil semua CPMK pada kurikulum, beserta MK induknya — diurutkan per semester
        $cpmkList = \App\Models\Cpmk::with('mataKuliah')
            ->where('id_kurikulum', $kurikulum->id)
            ->whereHas('mataKuliah')
            ->get()
            ->sortBy([
                fn ($a, $b) => ($a->mataKuliah->semester ?? 0) <=> ($b->mataKuliah->semester ?? 0),
                fn ($a, $b) => strcmp($a->mataKuliah->kode_mk ?? '', $b->mataKuliah->kode_mk ?? ''),
                fn ($a, $b) => strcmp($a->kode_cpmk, $b->kode_cpmk),
            ])->values();

        $existing = DB::table('pivot_cpl_cpmk')
            ->whereIn('id_cpmk', $cpmkList->pluck('id'))
            ->get()
            ->groupBy('id_cpmk')
            ->map(fn ($rows) => $rows->pluck('id_cpl')->toArray());

        return view('kurikulum.pivot.cpl-cpmk', compact('kurikulum', 'cplList', 'cpmkList', 'existing'));
    }

    // ── CPL ↔ BK ↔ MK (3 dimensi) ──────────────────────────────────────────────

    public function cplBkMk(Kurikulum $kurikulum)
    {
        $cplList = $kurikulum->cplProdi()->orderBy('urutan')->get();
        $bkList  = $kurikulum->bahanKajian()->orderBy('urutan')->get();
        $mkList  = $kurikulum->mataKuliah()->orderBy('semester')->orderBy('kode_mk')->get();
        $mkById  = $mkList->keyBy('id');

        $rows = DB::table('pivot_cpl_bk_mk')
            ->whereIn('id_cpl', $cplList->pluck('id'))
            ->whereIn('id_bk',  $bkList->pluck('id'))
            ->whereIn('id_mk',  $mkById->keys())
            ->orderBy('id_mk')
            ->get();

        $matriks = [];    // [bk_id][cpl_id] = [MataKuliah, ...]
        foreach ($rows as $r) {
            if (!isset($mkById[$r->id_mk])) continue;
            $matriks[$r->id_bk][$r->id_cpl][] = $mkById[$r->id_mk];
        }

        // Hitung coverage stats
        $totalRelasi = $rows->count();
        $bkTerisi   = count(array_filter($matriks, fn ($v) => !empty($v)));

        return view('kurikulum.pivot.cpl-bk-mk', compact(
            'kurikulum', 'cplList', 'bkList', 'mkList', 'mkById', 'matriks', 'totalRelasi', 'bkTerisi'
        ));
    }

    public function exportCplBkMk(Kurikulum $kurikulum, ExcelExportService $excel)
    {
        $cplList = $kurikulum->cplProdi()->orderBy('urutan')->get();
        $bkList  = $kurikulum->bahanKajian()->orderBy('urutan')->get();
        $mkList  = $kurikulum->mataKuliah()->orderBy('semester')->orderBy('kode_mk')->get();
        $mkById  = $mkList->keyBy('id');

        $rows = DB::table('pivot_cpl_bk_mk')
            ->whereIn('id_cpl', $cplList->pluck('id'))
            ->whereIn('id_bk',  $bkList->pluck('id'))
            ->whereIn('id_mk',  $mkById->keys())
            ->orderBy('id_mk')
            ->get();

        $matriks = [];
        foreach ($rows as $r) {
            if (!isset($mkById[$r->id_mk])) continue;
            $matriks[$r->id_bk][$r->id_cpl][] = $mkById[$r->id_mk]->kode_mk;
        }

        $headerRow = [['label' => 'BK \\ CPL', 'bg' => 'F59E0B']];
        foreach ($cplList as $cpl) {
            $headerRow[] = ['label' => $cpl->kode_cpl, 'bg' => 'F59E0B'];
        }

        $dataRows = [];
        foreach ($bkList as $bk) {
            $line = [$bk->kode_bk];
            foreach ($cplList as $cpl) {
                $line[] = implode(', ', $matriks[$bk->id][$cpl->id] ?? []);
            }
            $dataRows[] = $line;
        }

        $colWidths = [14];
        foreach ($cplList as $cpl) {
            $colWidths[] = 20;
        }

        return $excel->download("matriks-cpl-bk-mk-{$kurikulum->kode}.xlsx", [
            'CPL-BK-MK' => [
                'headerRows' => [$headerRow],
                'rows'       => $dataRows,
                'colWidths'  => $colWidths,
            ],
        ]);
    }

    /**
     * Sinkronkan ulang pivot_cpl_bk_mk dari matriks primer (CPL↔BK ∩ MK↔BK).
     * Dijalankan manual lewat tombol "Sync Ulang" di halaman matriks.
     */
    public function syncCplBkMk(Kurikulum $kurikulum)
    {
        if ($kurikulum->isArsip()) {
            return back()->with('error', 'Kurikulum sudah diarsipkan.');
        }

        // Full rebuild (non-additive): hapus semua lalu isi ulang dari primer
        $this->consistency->syncCplBkMk($kurikulum, additive: false);
        SyncMkCplJob::dispatch($kurikulum->id);

        return back()->with('success', 'Matriks CPL ↔ BK ↔ MK berhasil disinkronkan ulang dari CPL↔BK dan MK↔BK.');
    }
}
