<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\Cpmk;
use App\Models\CpmkPenilaian;
use App\Models\Kurikulum;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    // Helper: load all CPMK with relations for a kurikulum, grouped by CPL then MK
    private function loadCpmkData(Kurikulum $kurikulum): array
    {
        $cplList = $kurikulum->cplProdi()
            ->orderByRaw("FIELD(kategori,'Sikap','Keterampilan Umum','Keterampilan Khusus','Pengetahuan')")
            ->orderBy('urutan')
            ->get();

        $cpmkList = Cpmk::with([
                'mataKuliah',
                'penilaian',
                'subCpmk' => fn ($q) => $q->orderBy('urutan'),
            ])
            ->where('id_kurikulum', $kurikulum->id)
            ->whereIn('id_cpl', $cplList->pluck('id'))
            ->orderBy('kode_cpmk')
            ->get();

        return [$cplList, $cpmkList];
    }

    // VIEW 1: MK–CPMK–SubCPMK (editable overview)
    public function mkCpmkSubcpmk(Kurikulum $kurikulum)
    {
        $mkList = $kurikulum->mataKuliah()
            ->with(['cpmk' => fn ($q) => $q->with([
                'cplProdi',
                'penilaian',
                'subCpmk' => fn ($q2) => $q2->orderBy('urutan'),
            ])->orderBy('kode_cpmk')])
            ->orderBy('semester')
            ->orderBy('kode_mk')
            ->get();

        $cplList = $kurikulum->cplProdi()->orderBy('urutan')->get();

        return view('kurikulum.penilaian.mk-cpmk-subcpmk', compact('kurikulum', 'mkList', 'cplList'));
    }

    // VIEW 2: Teknik Penilaian CPMK (checkboxes matrix)
    public function teknikPenilaian(Kurikulum $kurikulum)
    {
        [$cplList, $cpmkList] = $this->loadCpmkData($kurikulum);
        $byCpl = $cpmkList->groupBy('id_cpl');

        return view('kurikulum.penilaian.teknik-penilaian', compact('kurikulum', 'cplList', 'byCpl'));
    }

    public function saveTeknikPenilaian(Request $request, Kurikulum $kurikulum)
    {
        // cpmk_ids[] = daftar semua CPMK yang ada di form (termasuk yang semua checkbox-nya unchecked)
        $allCpmkIds = $request->input('cpmk_ids', []);
        $data       = $request->input('penilaian', []);

        // Jika tidak ada cpmk_ids (fallback), ambil dari DB
        if (empty($allCpmkIds)) {
            [$cplList, $cpmkList] = $this->loadCpmkData($kurikulum);
            $allCpmkIds = $cpmkList->pluck('id')->toArray();
        }

        // Proses SETIAP CPMK yang ada di form — checked=true, unchecked=false
        foreach ($allCpmkIds as $cpmkId) {
            $cpmkId = (int) $cpmkId;

            // Pastikan CPMK milik kurikulum ini
            $valid = Cpmk::where('id', $cpmkId)
                ->where('id_kurikulum', $kurikulum->id)
                ->exists();
            if (! $valid) continue;

            $fields = $data[$cpmkId] ?? [];

            CpmkPenilaian::updateOrCreate(
                ['id_cpmk' => $cpmkId],
                [
                    'teknik_quiz'        => ! empty($fields['teknik_quiz']),
                    'teknik_observasi'   => ! empty($fields['teknik_observasi']),
                    'teknik_unjuk_kerja' => ! empty($fields['teknik_unjuk_kerja']),
                    'teknik_uts'         => ! empty($fields['teknik_uts']),
                    'teknik_uas'         => ! empty($fields['teknik_uas']),
                    'teknik_tes_lisan'   => ! empty($fields['teknik_tes_lisan']),
                ]
            );
        }

        return redirect()->route('kurikulum.penilaian.teknik', $kurikulum)
            ->with('success', 'Teknik penilaian berhasil disimpan.');
    }

    // VIEW 3: Tahap & Mekanisme Penilaian (Tabel 17)
    public function tahapMekanisme(Kurikulum $kurikulum)
    {
        $cplList = $kurikulum->cplProdi()
            ->orderByRaw("FIELD(kategori,'Sikap','Keterampilan Umum','Keterampilan Khusus','Pengetahuan')")
            ->orderBy('urutan')
            ->get();

        // Load all CPMK dengan MK dan penilaian
        $allCpmk = Cpmk::with(['mataKuliah:id,kode_mk,nama_mk,semester', 'penilaian'])
            ->where('id_kurikulum', $kurikulum->id)
            ->whereIn('id_cpl', $cplList->pluck('id'))
            ->orderBy('kode_cpmk')
            ->get();

        // Build tableData: CPL → [MK → [CPMK rows]]
        $tableData = [];
        foreach ($cplList as $cpl) {
            $cpmkForCpl = $allCpmk->where('id_cpl', $cpl->id);

            // Group by MK, sorted by semester then kode_mk
            $mkRows = [];
            foreach ($cpmkForCpl->groupBy('id_mk') as $mkId => $cpmks) {
                $mk = $cpmks->first()->mataKuliah;
                if (! $mk) continue;
                $mkRows[] = [
                    'mk'    => $mk,
                    'cpmks' => $cpmks->sortBy('kode_cpmk')->values(),
                ];
            }
            usort($mkRows, fn($a, $b) =>
                $a['mk']->semester <=> $b['mk']->semester ?: strcmp($a['mk']->kode_mk, $b['mk']->kode_mk)
            );

            $totalRows = array_sum(array_map(fn($m) => count($m['cpmks']), $mkRows));

            $tableData[] = [
                'cpl'       => $cpl,
                'mk_rows'   => $mkRows,
                'row_count' => max($totalRows, 1),
            ];
        }

        return view('kurikulum.penilaian.tahap-mekanisme', compact('kurikulum', 'cplList', 'tableData'));
    }

    public function saveTahapMekanisme(Request $request, Kurikulum $kurikulum)
    {
        $data = $request->input('penilaian', []);

        foreach ($data as $cpmkId => $fields) {
            $cpmk = Cpmk::where('id', $cpmkId)->where('id_kurikulum', $kurikulum->id)->first();
            if (! $cpmk) {
                continue;
            }

            // Hanya simpan tahap, instrumen, kriteria.
            // Teknik penilaian dikelola terpisah di halaman Teknik Penilaian.
            CpmkPenilaian::updateOrCreate(
                ['id_cpmk' => $cpmkId],
                [
                    'tahap_penilaian' => $fields['tahap_penilaian'] ?? 'Awal-Tengah Semester',
                    'instrumen'       => $fields['instrumen'] ?? null,
                    'kriteria'        => $fields['kriteria'] ?? null,
                    'teknik_quiz'        => ! empty($fields['teknik_quiz']),
                    'teknik_observasi'   => ! empty($fields['teknik_observasi']),
                    'teknik_unjuk_kerja' => ! empty($fields['teknik_unjuk_kerja']),
                    'teknik_uts'         => ! empty($fields['teknik_uts']),
                    'teknik_uas'         => ! empty($fields['teknik_uas']),
                    'teknik_tes_lisan'   => ! empty($fields['teknik_tes_lisan']),
                ]
            );
        }

        return redirect()->route('kurikulum.penilaian.tahap', $kurikulum)
            ->with('success', 'Tahap dan mekanisme penilaian berhasil disimpan.');
    }

    // VIEW 4: Bobot Penilaian
    public function bobotPenilaian(Kurikulum $kurikulum)
    {
        [$cplList, $cpmkList] = $this->loadCpmkData($kurikulum);
        $byCpl = $cpmkList->groupBy('id_cpl');

        return view('kurikulum.penilaian.bobot', compact('kurikulum', 'cplList', 'byCpl'));
    }

    public function saveBobotPenilaian(Request $request, Kurikulum $kurikulum)
    {
        $data = $request->input('penilaian', []);

        foreach ($data as $cpmkId => $fields) {
            $cpmk = Cpmk::where('id', $cpmkId)->where('id_kurikulum', $kurikulum->id)->first();
            if (! $cpmk) {
                continue;
            }

            CpmkPenilaian::updateOrCreate(
                ['id_cpmk' => $cpmkId],
                [
                    'bobot_quiz'        => $fields['bobot_quiz']        ?: null,
                    'bobot_observasi'   => $fields['bobot_observasi']   ?: null,
                    'bobot_unjuk_kerja' => $fields['bobot_unjuk_kerja'] ?: null,
                    'bobot_uts'         => $fields['bobot_uts']         ?: null,
                    'bobot_uas'         => $fields['bobot_uas']         ?: null,
                    'bobot_tes_lisan'   => $fields['bobot_tes_lisan']   ?: null,
                ]
            );
        }

        return redirect()->route('kurikulum.penilaian.bobot', $kurikulum)
            ->with('success', 'Bobot penilaian berhasil disimpan.');
    }

    // VIEW 5: Rumusan Nilai Akhir MK
    public function rumusanNilai(Kurikulum $kurikulum)
    {
        $mkList = $kurikulum->mataKuliah()
            ->with(['cpmk' => fn ($q) => $q
                ->with(['cplProdi', 'penilaian', 'subCpmk' => fn ($q2) => $q2->orderBy('urutan')])
                ->orderBy('kode_cpmk')])
            ->orderBy('semester')->orderBy('kode_mk')
            ->get();

        return view('kurikulum.penilaian.rumusan-nilai', compact('kurikulum', 'mkList'));
    }

    public function saveRumusanNilai(Request $request, Kurikulum $kurikulum)
    {
        $data = $request->input('skor_maks', []);
        foreach ($data as $cpmkId => $skor) {
            $cpmk = \App\Models\Cpmk::where('id', $cpmkId)->where('id_kurikulum', $kurikulum->id)->first();
            if (!$cpmk) continue;
            \App\Models\CpmkPenilaian::updateOrCreate(
                ['id_cpmk' => $cpmkId],
                ['skor_maks' => $skor ?: null]
            );
        }
        return redirect()->route('kurikulum.penilaian.rumusan', $kurikulum)
            ->with('success', 'Skor maks berhasil disimpan.');
    }

    // VIEW 6: Rumusan Nilai Akhir CPL
    public function rumusanNilaiCpl(Kurikulum $kurikulum)
    {
        $cplList = $kurikulum->cplProdi()
            ->orderByRaw("FIELD(kategori,'Sikap','Keterampilan Umum','Keterampilan Khusus','Pengetahuan')")
            ->orderBy('urutan')->get();

        // Load all CPMK with MK and penilaian
        $allCpmk = \App\Models\Cpmk::with(['mataKuliah', 'penilaian', 'subCpmk'])
            ->where('id_kurikulum', $kurikulum->id)
            ->whereIn('id_cpl', $cplList->pluck('id'))
            ->orderBy('kode_cpmk')
            ->get();

        // Build tableData: CPL → MK → [CPMK rows]
        $tableData = [];
        foreach ($cplList as $cpl) {
            $cpmkForCpl = $allCpmk->where('id_cpl', $cpl->id);

            // Group by MK
            $byMk   = $cpmkForCpl->groupBy('id_mk');
            $mkRows = [];
            foreach ($byMk as $mkId => $cpmks) {
                $mk = $cpmks->first()->mataKuliah;
                if (!$mk) continue;
                $rows = [];
                foreach ($cpmks->sortBy('kode_cpmk') as $cpmk) {
                    $p    = $cpmk->penilaian;
                    $skor = $p && !is_null($p->skor_maks) ? (float) $p->skor_maks : (float) $cpmk->subCpmk->sum('bobot');
                    $rows[] = ['cpmk' => $cpmk, 'skor_maks' => $skor];
                }
                $mkRows[] = ['mk' => $mk, 'cpmk_rows' => $rows, 'mk_total' => array_sum(array_column($rows, 'skor_maks'))];
            }

            // Sort mk by semester then kode_mk
            usort($mkRows, fn ($a, $b) => $a['mk']->semester <=> $b['mk']->semester ?: strcmp($a['mk']->kode_mk, $b['mk']->kode_mk));

            $cplTotal  = array_sum(array_column($mkRows, 'mk_total'));
            $tableData[] = [
                'cpl'       => $cpl,
                'mk_rows'   => $mkRows,
                'cpl_total' => $cplTotal,
                'row_count' => max(array_sum(array_map(fn ($m) => count($m['cpmk_rows']), $mkRows)), 1),
            ];
        }

        return view('kurikulum.penilaian.rumusan-nilai-cpl', compact('kurikulum', 'tableData'));
    }

    public function saveRumusanNilaiCpl(Request $request, Kurikulum $kurikulum)
    {
        // Same as saveRumusanNilai - skor_maks is shared
        $data = $request->input('skor_maks', []);
        foreach ($data as $cpmkId => $skor) {
            $cpmk = \App\Models\Cpmk::where('id', $cpmkId)->where('id_kurikulum', $kurikulum->id)->first();
            if (!$cpmk) continue;
            \App\Models\CpmkPenilaian::updateOrCreate(
                ['id_cpmk' => $cpmkId],
                ['skor_maks' => $skor ?: null]
            );
        }
        return redirect()->route('kurikulum.penilaian.rumusan-cpl', $kurikulum)
            ->with('success', 'Skor maks CPL berhasil disimpan.');
    }

    // ── PETA CPL–CPMK–MK Semester (Tabel 13) ────────────────────────────────

    /**
     * Tabel 13: CPL (rowspan) × CPMK (rowspan) × Semester columns.
     * Setiap sel berisi kode MK yang memiliki CPMK tersebut di semester itu.
     * Data langsung dari tabel cpmk (id_mk → mata_kuliah.semester).
     */
    public function petaSemester(Kurikulum $kurikulum)
    {
        $cplList = $kurikulum->cplProdi()
            ->orderByRaw("FIELD(kategori,'Sikap','Keterampilan Umum','Keterampilan Khusus','Pengetahuan')")
            ->orderBy('urutan')
            ->get();

        $allCpmk = Cpmk::with(['mataKuliah:id,kode_mk,nama_mk,semester,sks_teori,sks_praktikum'])
            ->where('id_kurikulum', $kurikulum->id)
            ->whereIn('id_cpl', $cplList->pluck('id'))
            ->orderBy('kode_cpmk')
            ->get();

        // Semua MK untuk selector tambah
        $mkList = $kurikulum->mataKuliah()
            ->orderBy('semester')->orderBy('kode_mk')
            ->get(['id', 'kode_mk', 'nama_mk', 'semester']);

        $semesterRange = range(1, 8);

        // Build tableData: CPL → [kode_cpmk → {deskripsi, semester → [{cpmk_id, mk}]}]
        $tableData = [];
        foreach ($cplList as $cpl) {
            $cpmkForCpl = $allCpmk->where('id_cpl', $cpl->id);
            $cpmkGroups = [];

            foreach ($cpmkForCpl->groupBy('kode_cpmk') as $kode => $records) {
                $semMap = [];
                foreach ($semesterRange as $smt) {
                    $inSmt = $records->filter(fn($r) => $r->mataKuliah && $r->mataKuliah->semester == $smt);
                    $semMap[$smt] = $inSmt->map(fn($r) => [
                        'cpmk_id' => $r->id,
                        'mk'      => $r->mataKuliah,
                    ])->values();
                }
                $cpmkGroups[] = [
                    'kode'      => $kode,
                    'deskripsi' => $records->first()->deskripsi,
                    'semesters' => $semMap,
                    'records'   => $records,
                ];
            }

            usort($cpmkGroups, fn($a, $b) => strcmp($a['kode'], $b['kode']));

            $tableData[] = [
                'cpl'        => $cpl,
                'cpmk_rows'  => $cpmkGroups,
                'row_count'  => max(count($cpmkGroups), 1),
            ];
        }

        return view('kurikulum.penilaian.peta-cpl-cpmk-semester',
            compact('kurikulum', 'tableData', 'semesterRange', 'mkList', 'cplList'));
    }

    /**
     * Tambah assignment: buat CPMK record baru untuk MK di semester tertentu.
     */
    public function addPetaSemester(Request $request, Kurikulum $kurikulum)
    {
        $validated = $request->validate([
            'id_cpl'     => 'required|exists:cpl_prodi,id',
            'kode_cpmk'  => 'required|string|max:20',
            'deskripsi'  => 'nullable|string',
            'id_mk'      => 'required|exists:mata_kuliah,id',
        ]);

        // Pastikan CPL dan MK milik kurikulum ini
        $cpl = $kurikulum->cplProdi()->find($validated['id_cpl']);
        $mk  = $kurikulum->mataKuliah()->find($validated['id_mk']);
        if (!$cpl || !$mk) {
            return back()->with('error', 'CPL atau MK tidak valid.');
        }

        // Hindari duplikat (kode_cpmk + id_mk sudah ada)
        $exists = Cpmk::where('id_kurikulum', $kurikulum->id)
            ->where('kode_cpmk', $validated['kode_cpmk'])
            ->where('id_mk', $validated['id_mk'])
            ->exists();
        if ($exists) {
            return back()->with('error', 'Relasi CPMK–MK ini sudah ada.');
        }

        // Ambil deskripsi dari sibling CPMK yang sama kodenya
        $sibling = Cpmk::where('id_kurikulum', $kurikulum->id)
            ->where('kode_cpmk', $validated['kode_cpmk'])
            ->where('id_cpl', $validated['id_cpl'])
            ->first();

        $urutan = Cpmk::where('id_mk', $mk->id)->max('urutan') + 1;

        Cpmk::create([
            'id_kurikulum' => $kurikulum->id,
            'id_cpl'       => $validated['id_cpl'],
            'id_mk'        => $validated['id_mk'],
            'kode_cpmk'    => $validated['kode_cpmk'],
            'deskripsi'    => $validated['deskripsi'] ?: ($sibling?->deskripsi ?? 'CPMK ' . $validated['kode_cpmk']),
            'urutan'       => $urutan,
        ]);

        return back()->with('success', $validated['kode_cpmk'] . ' berhasil ditambahkan ke ' . $mk->kode_mk . '.');
    }

    /**
     * Hapus assignment: hapus CPMK record spesifik.
     */
    public function removePetaSemester(Kurikulum $kurikulum, Cpmk $cpmk)
    {
        // Pastikan CPMK milik kurikulum ini
        if ($cpmk->id_kurikulum !== $kurikulum->id) {
            abort(403);
        }
        $kode = $cpmk->kode_cpmk;
        $cpmk->delete();

        return back()->with('success', $kode . ' berhasil dihapus dari MK.');
    }
}
