<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\Cpmk;
use App\Models\CplProdi;
use App\Models\EnrollmentMk;
use App\Models\HasilCpl;
use App\Models\HasilPl;
use App\Models\KomponenAsesmen;
use App\Models\Kurikulum;
use App\Models\MataKuliah;
use App\Models\SemesterAkademik;
use App\Models\User;
use App\Services\ExcelExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Halaman ringkasan/peta read-only yang men-agregasi data OBE
 * dari beberapa matriks primer + entitas (CPMK/SubCPMK/Komponen Asesmen).
 */
class OverviewController extends Controller
{
    /**
     * Peta Pemenuhan CPL — untuk setiap CPL, tampilkan MK yang memetakannya
     * (lewat hubungan CPL↔BK ∩ MK↔BK) dan jumlah CPMK yang ada.
     * Juga menyediakan peta visual CPL × Semester untuk tab "Peta Visual".
     */
    public function pemenuhanCpl(Kurikulum $kurikulum)
    {
        $cplList = $kurikulum->cplProdi()->orderBy('urutan')->get();
        $mkList  = $kurikulum->mataKuliah()->orderBy('semester')->orderBy('kode_mk')->get();

        // Build peta: cpl_id => { semester => [mk objects] }
        $peta = [];
        $semesterRange = range(1, 8);

        // From pivot_mk_cpl: which MK maps to which CPL
        $mkCplRows = DB::table('pivot_mk_cpl')
            ->whereIn('id_mk', $mkList->pluck('id'))
            ->whereIn('id_cpl', $cplList->pluck('id'))
            ->select('id_mk', 'id_cpl')
            ->distinct()
            ->get();

        $mkById = $mkList->keyBy('id');

        foreach ($cplList as $cpl) {
            $peta[$cpl->id] = array_fill_keys($semesterRange, []);
        }

        // Build existing: mk_id => [cpl_ids] — untuk matrix editing
        $existing = [];
        foreach ($mkCplRows as $row) {
            if (!isset($mkById[$row->id_mk])) continue;
            $existing[$row->id_mk][] = $row->id_cpl;
            $mk  = $mkById[$row->id_mk];
            $smt = $mk->semester;
            if (isset($peta[$row->id_cpl][$smt])) {
                $peta[$row->id_cpl][$smt][] = $mk;
            }
        }

        // Legacy pemenuhan data (for backward compat)
        $derived = DB::table('pivot_cpl_bk_mk')
            ->whereIn('id_cpl', $cplList->pluck('id'))
            ->whereIn('id_mk',  $mkList->pluck('id'))
            ->join('bahan_kajian', 'bahan_kajian.id', '=', 'pivot_cpl_bk_mk.id_bk')
            ->select('id_cpl', 'id_mk', 'bahan_kajian.kode_bk')
            ->get()
            ->groupBy('id_cpl');

        $cpmkCounts = DB::table('cpmk')
            ->whereIn('id_kurikulum', [$kurikulum->id])
            ->select('id_mk', 'id_cpl', DB::raw('COUNT(*) as cnt'))
            ->groupBy('id_mk', 'id_cpl')
            ->get()
            ->groupBy(fn ($r) => $r->id_cpl . '-' . $r->id_mk)
            ->map(fn ($g) => $g->first()->cnt);

        $pemenuhan = [];
        foreach ($cplList as $cpl) {
            $perCpl = [];
            $rows = $derived->get($cpl->id, collect());
            foreach ($rows->groupBy('id_mk') as $mkId => $bkRows) {
                if (!isset($mkById[$mkId])) continue;
                $perCpl[] = [
                    'mk'         => $mkById[$mkId],
                    'bk_codes'   => $bkRows->pluck('kode_bk')->unique()->values()->all(),
                    'cpmk_count' => (int) ($cpmkCounts->get($cpl->id . '-' . $mkId, 0)),
                ];
            }
            $pemenuhan[$cpl->id] = $perCpl;
        }

        // Build CPMK map: id_mk → id_cpl → [kode_cpmk...] (unique, sorted)
        $cpmkForMatrix = Cpmk::where('id_kurikulum', $kurikulum->id)
            ->select('id_mk', 'id_cpl', 'kode_cpmk')
            ->orderBy('kode_cpmk')
            ->get();

        $cpmkMap = [];
        foreach ($cpmkForMatrix as $row) {
            if (!isset($cpmkMap[$row->id_mk][$row->id_cpl])) {
                $cpmkMap[$row->id_mk][$row->id_cpl] = [];
            }
            if (!in_array($row->kode_cpmk, $cpmkMap[$row->id_mk][$row->id_cpl])) {
                $cpmkMap[$row->id_mk][$row->id_cpl][] = $row->kode_cpmk;
            }
        }

        return view('kurikulum.overview.pemenuhan-cpl', compact(
            'kurikulum', 'cplList', 'pemenuhan', 'peta', 'semesterRange', 'mkList', 'cpmkMap', 'existing'
        ));
    }

    /**
     * Pemetaan CPL–CPMK–MK — Tabel 12 dari buku OBE.
     * Data diambil segar dari DB setiap request (tidak ada caching).
     * Baris: CPL (rowspan) → kode CPMK unik → MK codes.
     * Kolom: No | CPL | Deskripsi CPL | Kode CPMK | Deskripsi CPMK | MK
     */
    public function cplCpmkMk(Kurikulum $kurikulum)
    {
        // 1. CPL diurutkan sesuai kategori standar OBE
        $cplList = $kurikulum->cplProdi()
            ->orderByRaw("FIELD(kategori,'Sikap','Keterampilan Umum','Keterampilan Khusus','Pengetahuan')")
            ->orderBy('urutan')
            ->get();

        // Daftar MK untuk modal
        $mkList = $kurikulum->mataKuliah()
            ->orderBy('semester')
            ->orderBy('kode_mk')
            ->get();

        if ($cplList->isEmpty()) {
            return view('kurikulum.overview.cpl-cpmk-mk', [
                'kurikulum' => $kurikulum,
                'tableData' => [],
                'mkList'    => $mkList,
                'cplList'   => $cplList,
            ]);
        }

        // 2. Load SEMUA CPMK beserta MK-nya
        $allCpmk = Cpmk::with(['mataKuliah:id,kode_mk,nama_mk,semester,sks_teori,sks_praktikum,sks_total'])
            ->where('id_kurikulum', $kurikulum->id)
            ->whereIn('id_cpl', $cplList->pluck('id'))
            ->orderBy('kode_cpmk')
            ->orderBy('urutan')
            ->orderBy('id')
            ->get();

        // 3. Build tableData: CPL → CPMK groups (grouped by kode_cpmk)
        //    Satu baris per kode CPMK; kolom MK menampilkan SEMUA MK yang mengajarkan CPMK tsb.
        $tableData = [];
        foreach ($cplList as $cpl) {
            $cpmkForCpl = $allCpmk->where('id_cpl', $cpl->id);

            // Group by kode_cpmk → satu entry per kode unik
            $groups = $cpmkForCpl
                ->groupBy('kode_cpmk')
                ->map(function ($records, $kode) {
                    $first = $records->sortBy('id')->first();
                    return [
                        'kode_cpmk'    => $kode,
                        'deskripsi'    => $first->deskripsi,
                        'level_bloom'  => $first->level_bloom,
                        'first_id'     => $first->id,          // untuk link edit
                        'first_mk_id'  => $first->id_mk,       // untuk link edit
                        'first_record' => $first,
                        'records'      => $records->values(),  // semua record (1 per MK)
                        'mks'          => $records->map(fn ($r) => $r->mataKuliah)
                                                   ->filter()
                                                   ->unique('id')
                                                   ->values(),
                    ];
                })
                ->values();

            $tableData[] = [
                'cpl'         => $cpl,
                'cpmk_groups' => $groups,
                'row_count'   => max($groups->count(), 1),
            ];
        }

        return view('kurikulum.overview.cpl-cpmk-mk', compact('kurikulum', 'tableData', 'mkList', 'cplList'));
    }

    public function storeCpmk(Request $request, Kurikulum $kurikulum)
    {
        $bloomOptions = ['C1','C2','C3','C4','C5','C6','A1','A2','A3','A4','A5','P1','P2','P3','P4','P5'];

        $validated = $request->validate([
            'id_cpl'      => 'required|exists:cpl_prodi,id',
            'id_mk'       => 'required|exists:mata_kuliah,id',
            'kode_cpmk'   => 'nullable|string|max:50',
            'deskripsi'   => 'required|string',
            'level_bloom' => 'nullable|in:' . implode(',', $bloomOptions),
        ]);

        $mk  = MataKuliah::where('id', $validated['id_mk'])->where('id_kurikulum', $kurikulum->id)->firstOrFail();
        $cpl = CplProdi::where('id', $validated['id_cpl'])->where('id_kurikulum', $kurikulum->id)->firstOrFail();

        // Mode: tambah MK ke CPMK yang sudah ada (kode dan deskripsi diambil dari existing)
        $existingKode = $request->input('existing_kode_cpmk');
        if ($existingKode) {
            $existing = Cpmk::where('id_kurikulum', $kurikulum->id)
                ->where('kode_cpmk', $existingKode)
                ->first();
            if ($existing) {
                $validated['kode_cpmk']   = $existing->kode_cpmk;
                $validated['deskripsi']   = $existing->deskripsi;
                $validated['level_bloom'] = $existing->level_bloom;
                $validated['id_cpl']      = $existing->id_cpl;
            }
        }

        if (empty($validated['kode_cpmk'])) {
            $cplSeq    = str_pad($cpl->urutan, 2, '0', STR_PAD_LEFT);
            $cpmkCount = Cpmk::where('id_cpl', $cpl->id)->where('id_kurikulum', $kurikulum->id)->withTrashed()->count() + 1;
            $validated['kode_cpmk'] = 'CPMK' . $cplSeq . $cpmkCount;
        }

        $validated['urutan']       = ($mk->cpmk()->max('urutan') ?? 0) + 1;
        $validated['id_kurikulum'] = $kurikulum->id;

        // Cegah duplikat MK untuk CPMK yang sama
        $alreadyExists = Cpmk::where('id_kurikulum', $kurikulum->id)
            ->where('kode_cpmk', $validated['kode_cpmk'])
            ->where('id_mk', $validated['id_mk'])
            ->exists();

        if ($alreadyExists) {
            return redirect()
                ->route('kurikulum.overview.cpl-cpmk-mk', $kurikulum)
                ->with('error', 'MK ini sudah dikaitkan dengan CPMK ' . $validated['kode_cpmk'] . '.');
        }

        Cpmk::create($validated);

        return redirect()
            ->route('kurikulum.overview.cpl-cpmk-mk', $kurikulum)
            ->with('success', 'CPMK berhasil ditambahkan.');
    }

    public function updateCpmk(Request $request, Kurikulum $kurikulum, Cpmk $cpmk)
    {
        abort_unless($cpmk->id_kurikulum === $kurikulum->id, 403);

        $bloomOptions = ['C1','C2','C3','C4','C5','C6','A1','A2','A3','A4','A5','P1','P2','P3','P4','P5'];

        $validated = $request->validate([
            'kode_cpmk' => 'required|string|max:50',
            'deskripsi' => 'required|string',
        ]);

        // Update semua record dengan kode_cpmk yang sama dalam kurikulum ini
        // (level_bloom tidak diubah — field tidak ditampilkan di form edit)
        Cpmk::where('id_kurikulum', $kurikulum->id)
            ->where('kode_cpmk', $cpmk->kode_cpmk)
            ->update([
                'kode_cpmk' => $validated['kode_cpmk'],
                'deskripsi' => $validated['deskripsi'],
            ]);

        return redirect()
            ->route('kurikulum.overview.cpl-cpmk-mk', $kurikulum)
            ->with('success', 'CPMK ' . $validated['kode_cpmk'] . ' berhasil diperbarui.');
    }

    public function destroyCpmk(Kurikulum $kurikulum, Cpmk $cpmk)
    {
        abort_unless($cpmk->id_kurikulum === $kurikulum->id, 403);
        $cpmk->delete();

        return redirect()
            ->route('kurikulum.overview.cpl-cpmk-mk', $kurikulum)
            ->with('success', 'CPMK berhasil dihapus.');
    }

    /**
     * Pemetaan MK → CPMK → Sub-CPMK (overview, satu halaman semua MK).
     */
    public function cpmkOverview(Kurikulum $kurikulum)
    {
        $mkList = $kurikulum->mataKuliah()
            ->with(['cpmk.subCpmk' => fn ($q) => $q->orderBy('urutan'), 'cpmk.cplProdi'])
            ->orderBy('semester')->orderBy('kode_mk')
            ->get();

        return view('kurikulum.overview.cpmk', compact('kurikulum', 'mkList'));
    }

    /**
     * Rumusan Akhir MK — formula komposisi nilai per MK
     * berdasarkan KomponenAsesmen yang sudah didefinisikan dosen.
     * Sekaligus Rumusan Akhir CPL (agregasi capaian per CPL dari CPMK).
     */
    public function rumusanAkhir(Kurikulum $kurikulum)
    {
        $mkList = $kurikulum->mataKuliah()
            ->with(['cpmk.cplProdi'])
            ->orderBy('semester')->orderBy('kode_mk')
            ->get();

        // Komponen asesmen per MK (group by mk + semester aktif)
        $komponen = KomponenAsesmen::with(['subCpmk.cpmk'])
            ->whereIn('id_mk', $mkList->pluck('id'))
            ->get()
            ->groupBy('id_mk');

        // CPMK per CPL untuk halaman Rumusan Akhir CPL
        $cplList = $kurikulum->cplProdi()->orderBy('urutan')->get();
        $cpmkPerCpl = Cpmk::with(['mataKuliah'])
            ->whereIn('id_cpl', $cplList->pluck('id'))
            ->where('id_kurikulum', $kurikulum->id)
            ->get()
            ->groupBy('id_cpl');

        return view('kurikulum.overview.rumusan-akhir', compact('kurikulum', 'mkList', 'komponen', 'cplList', 'cpmkPerCpl'));
    }

    /**
     * Ketercapaian PL — agregasi hasil_pl per PL.
     */
    public function ketercapaianPl(Kurikulum $kurikulum)
    {
        $plList = $kurikulum->pl()->orderBy('urutan')->get();

        // Agregasi nilai rata-rata per PL
        $hasilPerPl = HasilPl::whereIn('id_pl', $plList->pluck('id'))
            ->select('id_pl',
                DB::raw('AVG(nilai_pl) as rata_nilai'),
                DB::raw('COUNT(*) as total_record'),
                DB::raw('SUM(CASE WHEN status_tercapai = 1 THEN 1 ELSE 0 END) as tercapai_count')
            )
            ->groupBy('id_pl')
            ->get()
            ->keyBy('id_pl');

        return view('kurikulum.overview.ketercapaian-pl', compact('kurikulum', 'plList', 'hasilPerPl'));
    }

    /**
     * Organisasi MK — visualisasi MK per semester (org-chart style grid).
     */
    /**
     * Organisasi MK — visualisasi MK per semester + MK Pilihan per konsentrasi.
     */
    public function organisasiMk(Kurikulum $kurikulum)
    {
        $mkList = $kurikulum->mataKuliah()
            ->orderBy('semester')
            ->orderBy('kategori_mk')
            ->orderBy('kode_mk')
            ->get();

        $bySemester = $mkList->groupBy('semester');

        // MK Pilihan dikelompokkan per konsentrasi untuk bagian bawah halaman
        $mkPilihan = $mkList
            ->where('kategori_mk', 'Pilihan')
            ->filter(fn ($mk) => !empty($mk->konsentrasi))
            ->groupBy('konsentrasi');

        // Statistik per kategori
        $statPerKategori = $mkList->groupBy('kategori_mk')->map(fn ($mks) => [
            'count'    => $mks->count(),
            'sks_total'=> $mks->sum(fn ($mk) => ($mk->sks_teori ?? 0) + ($mk->sks_praktikum ?? 0)),
        ]);

        $totalSks = $mkList->sum(fn ($mk) => ($mk->sks_teori ?? 0) + ($mk->sks_praktikum ?? 0));

        return view('kurikulum.overview.organisasi-mk', compact(
            'kurikulum', 'mkList', 'bySemester', 'mkPilihan', 'statPerKategori', 'totalSks'
        ));
    }

    // ── EXPORT METHODS ────────────────────────────────────────────────────────

    public function exportOrganisasiMk(Kurikulum $kurikulum, ExcelExportService $excel)
    {
        $mkList = $kurikulum->mataKuliah()
            ->orderBy('semester')->orderBy('kategori_mk')->orderBy('kode_mk')->get();

        $green = '16A34A';
        $header = [
            ['label' => 'Semester', 'bg' => $green], ['label' => 'Kode MK', 'bg' => $green],
            ['label' => 'Nama Mata Kuliah', 'bg' => $green], ['label' => 'SKS Teori', 'bg' => $green],
            ['label' => 'SKS Praktikum', 'bg' => $green], ['label' => 'Total SKS', 'bg' => $green],
            ['label' => 'Kategori', 'bg' => $green], ['label' => 'Prasyarat', 'bg' => $green],
            ['label' => 'Konsentrasi', 'bg' => $green],
        ];

        $rows = [];
        foreach ($mkList as $mk) {
            $rows[] = [
                $mk->semester, $mk->kode_mk, $mk->nama_mk,
                $mk->sks_teori ?? 0, $mk->sks_praktikum ?? 0,
                ($mk->sks_teori ?? 0) + ($mk->sks_praktikum ?? 0),
                $mk->kategori_mk ?? '—', $mk->kode_prasyarat ?? '—', $mk->konsentrasi ?? '—',
            ];
        }

        return $excel->download("organisasi-mk-{$kurikulum->kode}.xlsx", [
            'Organisasi MK' => ['headerRows' => [$header], 'rows' => $rows, 'colWidths' => [10,14,45,10,13,10,14,14,20]],
        ]);
    }

    public function exportPemenuhanCpl(Kurikulum $kurikulum, ExcelExportService $excel)
    {
        $cplList = $kurikulum->cplProdi()->orderBy('urutan')->get();
        $mkList  = $kurikulum->mataKuliah()->orderBy('semester')->orderBy('kode_mk')->get();

        $mkCplRows = DB::table('pivot_mk_cpl')
            ->whereIn('id_mk', $mkList->pluck('id'))
            ->whereIn('id_cpl', $cplList->pluck('id'))
            ->select('id_mk', 'id_cpl')->distinct()->get();

        $mkById = $mkList->keyBy('id');
        $byMk   = $mkCplRows->groupBy('id_mk');

        $blue = '2563EB';
        $header = [
            ['label' => 'Kode CPL', 'bg' => $blue], ['label' => 'Deskripsi CPL', 'bg' => $blue],
            ['label' => 'Kode MK', 'bg' => $blue], ['label' => 'Nama MK', 'bg' => $blue],
            ['label' => 'Semester', 'bg' => $blue],
        ];

        $rows = [];
        foreach ($cplList as $cpl) {
            $linked = $mkCplRows->where('id_cpl', $cpl->id);
            if ($linked->isEmpty()) {
                $rows[] = [$cpl->kode_cpl, $cpl->deskripsi, '—', '—', '—'];
            } else {
                foreach ($linked as $row) {
                    $mk = $mkById[$row->id_mk] ?? null;
                    $rows[] = [$cpl->kode_cpl, $cpl->deskripsi, $mk?->kode_mk ?? '—', $mk?->nama_mk ?? '—', $mk?->semester ?? '—'];
                }
            }
        }

        return $excel->download("pemenuhan-cpl-{$kurikulum->kode}.xlsx", [
            'Pemenuhan CPL' => ['headerRows' => [$header], 'rows' => $rows, 'colWidths' => [12,60,14,40,10]],
        ]);
    }

    public function exportCplCpmkMk(Kurikulum $kurikulum, ExcelExportService $excel)
    {
        $cplList = $kurikulum->cplProdi()
            ->orderByRaw("FIELD(kategori,'Sikap','Keterampilan Umum','Keterampilan Khusus','Pengetahuan')")
            ->orderBy('urutan')->get();

        $allCpmk = Cpmk::with(['mataKuliah:id,kode_mk,nama_mk'])
            ->where('id_kurikulum', $kurikulum->id)
            ->whereIn('id_cpl', $cplList->pluck('id'))
            ->orderBy('kode_cpmk')->get();

        $blue = '1D4ED8';
        $header = [
            ['label' => 'Kode CPL', 'bg' => $blue], ['label' => 'Kategori CPL', 'bg' => $blue],
            ['label' => 'Deskripsi CPL', 'bg' => $blue], ['label' => 'Kode CPMK', 'bg' => $blue],
            ['label' => 'Deskripsi CPMK', 'bg' => $blue], ['label' => 'Mata Kuliah', 'bg' => $blue],
        ];

        $rows = [];
        foreach ($cplList as $cpl) {
            $groups = $allCpmk->where('id_cpl', $cpl->id)->groupBy('kode_cpmk');
            if ($groups->isEmpty()) {
                $rows[] = [$cpl->kode_cpl, $cpl->kategori ?? '—', $cpl->deskripsi, '—', '—', '—'];
            } else {
                foreach ($groups as $kode => $records) {
                    $mks = $records->map(fn ($r) => $r->mataKuliah?->kode_mk)->filter()->unique()->implode(', ');
                    $rows[] = [
                        $cpl->kode_cpl, $cpl->kategori ?? '—', $cpl->deskripsi,
                        $kode, $records->first()->deskripsi, $mks ?: '—',
                    ];
                }
            }
        }

        return $excel->download("peta-cpl-cpmk-mk-{$kurikulum->kode}.xlsx", [
            'Peta CPL-CPMK-MK' => ['headerRows' => [$header], 'rows' => $rows, 'colWidths' => [12,20,60,14,60,20]],
        ]);
    }

    public function radarKetercapaian(Kurikulum $kurikulum, Request $request)
    {
        $semesterList = SemesterAkademik::orderByDesc('id')->get();
        $semester = $request->filled('semester')
            ? $semesterList->firstWhere('id', (int) $request->semester)
            : ($semesterList->firstWhere('is_aktif', true) ?? $semesterList->first());

        $cplList = $kurikulum->cplProdi()->orderBy('urutan')->get();
        $plList  = $kurikulum->pl()->orderBy('urutan')->get();

        // Individual mahasiswa
        $selectedMhsId = $request->filled('mahasiswa') ? (int) $request->mahasiswa : null;
        $selectedMhs   = $selectedMhsId ? User::find($selectedMhsId) : null;

        $mahasiswaList = collect();
        if ($semester) {
            $mkIds = $kurikulum->mataKuliah()->pluck('id');
            $mhsIds = EnrollmentMk::whereIn('id_mk', $mkIds)
                ->where('id_semester', $semester->id)
                ->where('status', 'aktif')
                ->pluck('id_mahasiswa')
                ->unique();
            $mahasiswaList = User::whereIn('id', $mhsIds)->orderBy('name')->get(['id','name','identifier','tahun_masuk']);
        }

        // CPL data per individu
        $cplIndividuData = [];
        if ($selectedMhs && $semester) {
            foreach ($cplList as $cpl) {
                $hasil = HasilCpl::where('id_mahasiswa', $selectedMhs->id)
                    ->where('id_cpl', $cpl->id)
                    ->where('id_semester', $semester->id)
                    ->first();
                $cplIndividuData[] = $hasil ? round((float) $hasil->nilai_cpl, 1) : 0;
            }
        }

        // Angkatan filter
        $angkatanList = User::whereIn('id', $mahasiswaList->pluck('id'))
            ->whereNotNull('tahun_masuk')
            ->distinct()->orderBy('tahun_masuk')->pluck('tahun_masuk');

        $selectedAngkatan = $request->filled('angkatan') ? (int) $request->angkatan : null;

        // CPL data per angkatan (rata-rata)
        $cplAngkatanData = [];
        if ($selectedAngkatan && $semester) {
            $angkatanMhsIds = User::whereIn('id', $mahasiswaList->pluck('id'))
                ->where('tahun_masuk', $selectedAngkatan)->pluck('id');
            foreach ($cplList as $cpl) {
                $avg = HasilCpl::whereIn('id_mahasiswa', $angkatanMhsIds)
                    ->where('id_cpl', $cpl->id)
                    ->where('id_semester', $semester->id)
                    ->avg('nilai_cpl');
                $cplAngkatanData[] = $avg ? round((float) $avg, 1) : 0;
            }
        }

        return view('kurikulum.overview.radar-ketercapaian', compact(
            'kurikulum', 'semester', 'semesterList',
            'cplList', 'plList',
            'mahasiswaList', 'selectedMhs', 'selectedMhsId',
            'cplIndividuData',
            'angkatanList', 'selectedAngkatan', 'cplAngkatanData',
        ));
    }
}
