<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\Cpmk;
use App\Models\CpmkPenilaian;
use App\Models\Kurikulum;
use App\Services\ExcelExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function exportMkCpmkSubcpmk(Kurikulum $kurikulum, ExcelExportService $excel)
    {
        $mkList = $kurikulum->mataKuliah()
            ->with(['cpmk' => fn ($q) => $q->with([
                'cplProdi',
                'subCpmk' => fn ($q2) => $q2->orderBy('urutan'),
            ])->orderBy('kode_cpmk')])
            ->orderBy('semester')
            ->orderBy('kode_mk')
            ->get();

        $headerRow = [
            ['label' => 'Kode MK', 'bg' => 'F59E0B'],
            ['label' => 'Kode CPMK', 'bg' => 'F59E0B'],
            ['label' => 'Deskripsi CPMK', 'bg' => 'F59E0B'],
            ['label' => 'CPL', 'bg' => 'F59E0B'],
            ['label' => 'Kode Sub-CPMK', 'bg' => 'F59E0B'],
            ['label' => 'Deskripsi Sub-CPMK', 'bg' => 'F59E0B'],
            ['label' => 'Bobot', 'bg' => 'F59E0B'],
        ];

        $rows = [];
        foreach ($mkList as $mk) {
            foreach ($mk->cpmk as $cpmk) {
                $cplKode = $cpmk->cplProdi->kode_cpl ?? '';

                if ($cpmk->subCpmk->isEmpty()) {
                    $rows[] = [$mk->kode_mk, $cpmk->kode_cpmk, $cpmk->deskripsi, $cplKode, '', '', ''];
                    continue;
                }

                foreach ($cpmk->subCpmk as $sub) {
                    $rows[] = [
                        $mk->kode_mk,
                        $cpmk->kode_cpmk,
                        $cpmk->deskripsi,
                        $cplKode,
                        $sub->kode_sub_cpmk,
                        $sub->deskripsi,
                        $sub->bobot,
                    ];
                }
            }
        }

        return $excel->download("mk-cpmk-subcpmk-{$kurikulum->kode}.xlsx", [
            'MK-CPMK-SubCPMK' => [
                'headerRows' => [$headerRow],
                'rows'       => $rows,
                'colWidths'  => [12, 14, 50, 10, 14, 50, 10],
            ],
        ]);
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
                    'skor_maks'       => isset($fields['skor_maks']) && $fields['skor_maks'] !== '' ? $fields['skor_maks'] : null,
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

    // VIEW 4b: Bobot Penilaian — diurutkan per MK (Tabel 18a)
    public function bobotMk(Kurikulum $kurikulum)
    {
        $mkList = $kurikulum->mataKuliah()
            ->with(['cpmk' => fn ($q) => $q
                ->with(['cplProdi', 'penilaian'])
                ->orderBy('kode_cpmk')])
            ->orderBy('semester')
            ->orderBy('kode_mk')
            ->get();

        // Build tableData: MK → [CPL → [CPMK rows]]
        $tableData = [];
        foreach ($mkList as $mk) {
            if ($mk->cpmk->isEmpty()) continue;

            // Group CPMK by CPL
            $cplRows = [];
            foreach ($mk->cpmk->groupBy('id_cpl') as $cplId => $cpmks) {
                $cpl = $cpmks->first()->cplProdi;
                if (!$cpl) continue;
                $cplRows[] = [
                    'cpl'   => $cpl,
                    'cpmks' => $cpmks->sortBy('kode_cpmk')->values(),
                ];
            }
            // Sort by CPL kode
            usort($cplRows, fn ($a, $b) => strcmp($a['cpl']->kode_cpl, $b['cpl']->kode_cpl));

            $totalRows = array_sum(array_map(fn ($r) => count($r['cpmks']), $cplRows));
            if ($totalRows === 0) continue;

            $tableData[] = [
                'mk'        => $mk,
                'cpl_rows'  => $cplRows,
                'row_count' => $totalRows,
            ];
        }

        return view('kurikulum.penilaian.bobot-mk', compact('kurikulum', 'mkList', 'tableData'));
    }

    public function saveBobotMk(Request $request, Kurikulum $kurikulum)
    {
        // Same logic as saveBobotPenilaian — saves individual bobot fields
        $data = $request->input('penilaian', []);
        foreach ($data as $cpmkId => $fields) {
            $cpmk = Cpmk::where('id', $cpmkId)->where('id_kurikulum', $kurikulum->id)->first();
            if (!$cpmk) continue;
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
        return redirect()->route('kurikulum.penilaian.bobot-mk', $kurikulum)
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

        // Semua MK untuk tabel dan selector tambah
        $mkList = $kurikulum->mataKuliah()
            ->orderBy('semester')->orderBy('kode_mk')
            ->get(['id', 'kode_mk', 'nama_mk', 'semester', 'sks_teori', 'sks_praktikum', 'sks_total']);

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

    // ── EXPORT METHODS ────────────────────────────────────────────────────────

    public function exportTeknikPenilaian(Kurikulum $kurikulum, ExcelExportService $excel)
    {
        [$cplList, $cpmkList] = $this->loadCpmkData($kurikulum);

        $blue = '3B82F6';
        $header = [
            ['label' => 'CPL', 'bg' => $blue],
            ['label' => 'Kode CPMK', 'bg' => $blue],
            ['label' => 'Deskripsi CPMK', 'bg' => $blue],
            ['label' => 'Mata Kuliah', 'bg' => $blue],
            ['label' => 'Quiz', 'bg' => $blue],
            ['label' => 'Observasi', 'bg' => $blue],
            ['label' => 'Unjuk Kerja', 'bg' => $blue],
            ['label' => 'UTS', 'bg' => $blue],
            ['label' => 'UAS', 'bg' => $blue],
            ['label' => 'Tes Lisan', 'bg' => $blue],
        ];

        $rows = [];
        foreach ($cplList as $cpl) {
            foreach ($cpmkList->where('id_cpl', $cpl->id) as $c) {
                $p = $c->penilaian;
                $rows[] = [
                    $cpl->kode_cpl,
                    $c->kode_cpmk,
                    $c->deskripsi,
                    $c->mataKuliah?->kode_mk ?? '—',
                    $p && $p->teknik_quiz ? 'Ya' : '',
                    $p && $p->teknik_observasi ? 'Ya' : '',
                    $p && $p->teknik_unjuk_kerja ? 'Ya' : '',
                    $p && $p->teknik_uts ? 'Ya' : '',
                    $p && $p->teknik_uas ? 'Ya' : '',
                    $p && $p->teknik_tes_lisan ? 'Ya' : '',
                ];
            }
        }

        return $excel->download("teknik-penilaian-{$kurikulum->kode}.xlsx", [
            'Teknik Penilaian' => ['headerRows' => [$header], 'rows' => $rows, 'colWidths' => [12,14,50,14,8,10,12,8,8,10]],
        ]);
    }

    public function exportTahapMekanisme(Kurikulum $kurikulum, ExcelExportService $excel)
    {
        $cplList = $kurikulum->cplProdi()->orderByRaw("FIELD(kategori,'Sikap','Keterampilan Umum','Keterampilan Khusus','Pengetahuan')")->orderBy('urutan')->get();
        $allCpmk = Cpmk::with(['mataKuliah:id,kode_mk,nama_mk,semester', 'penilaian'])
            ->where('id_kurikulum', $kurikulum->id)
            ->whereIn('id_cpl', $cplList->pluck('id'))
            ->orderBy('kode_cpmk')->get();

        $blue = '1D4ED8';
        $header = [
            ['label' => 'CPL', 'bg' => $blue], ['label' => 'MK', 'bg' => $blue], ['label' => 'Semester', 'bg' => $blue],
            ['label' => 'Kode CPMK', 'bg' => $blue], ['label' => 'Deskripsi', 'bg' => $blue],
            ['label' => 'Tahap Penilaian', 'bg' => $blue], ['label' => 'Instrumen', 'bg' => $blue],
            ['label' => 'Kriteria', 'bg' => $blue], ['label' => 'Skor Maks', 'bg' => $blue],
        ];

        $rows = [];
        foreach ($cplList as $cpl) {
            foreach ($allCpmk->where('id_cpl', $cpl->id)->sortBy('kode_cpmk') as $c) {
                $p = $c->penilaian;
                $rows[] = [
                    $cpl->kode_cpl,
                    $c->mataKuliah?->kode_mk ?? '—',
                    $c->mataKuliah?->semester ?? '—',
                    $c->kode_cpmk,
                    $c->deskripsi,
                    $p?->tahap_penilaian ?? '—',
                    $p?->instrumen ?? '—',
                    $p?->kriteria ?? '—',
                    $p?->skor_maks ?? '—',
                ];
            }
        }

        return $excel->download("tahap-mekanisme-{$kurikulum->kode}.xlsx", [
            'Tahap & Mekanisme' => ['headerRows' => [$header], 'rows' => $rows, 'colWidths' => [12,12,10,14,50,24,24,40,10]],
        ]);
    }

    public function exportBobotPenilaian(Kurikulum $kurikulum, ExcelExportService $excel)
    {
        [$cplList, $cpmkList] = $this->loadCpmkData($kurikulum);

        $blue = '1E40AF';
        $header = [
            ['label' => 'CPL', 'bg' => $blue], ['label' => 'Kode CPMK', 'bg' => $blue],
            ['label' => 'Deskripsi', 'bg' => $blue], ['label' => 'MK', 'bg' => $blue],
            ['label' => 'Quiz (%)', 'bg' => $blue], ['label' => 'Observasi (%)', 'bg' => $blue],
            ['label' => 'Unjuk Kerja (%)', 'bg' => $blue], ['label' => 'UTS (%)', 'bg' => $blue],
            ['label' => 'UAS (%)', 'bg' => $blue], ['label' => 'Tes Lisan (%)', 'bg' => $blue],
        ];

        $rows = [];
        foreach ($cplList as $cpl) {
            foreach ($cpmkList->where('id_cpl', $cpl->id) as $c) {
                $p = $c->penilaian;
                $rows[] = [
                    $cpl->kode_cpl, $c->kode_cpmk, $c->deskripsi, $c->mataKuliah?->kode_mk ?? '—',
                    $p?->bobot_quiz ?? '', $p?->bobot_observasi ?? '', $p?->bobot_unjuk_kerja ?? '',
                    $p?->bobot_uts ?? '', $p?->bobot_uas ?? '', $p?->bobot_tes_lisan ?? '',
                ];
            }
        }

        return $excel->download("bobot-penilaian-cpl-{$kurikulum->kode}.xlsx", [
            'Bobot Penilaian CPL' => ['headerRows' => [$header], 'rows' => $rows, 'colWidths' => [12,14,50,14,10,12,14,10,10,12]],
        ]);
    }

    public function exportBobotMk(Kurikulum $kurikulum, ExcelExportService $excel)
    {
        $mkList = $kurikulum->mataKuliah()
            ->with(['cpmk' => fn ($q) => $q->with(['cplProdi', 'penilaian'])->orderBy('kode_cpmk')])
            ->orderBy('semester')->orderBy('kode_mk')->get();

        $blue = '1E3A8A';
        $header = [
            ['label' => 'MK', 'bg' => $blue], ['label' => 'Semester', 'bg' => $blue],
            ['label' => 'CPL', 'bg' => $blue], ['label' => 'Kode CPMK', 'bg' => $blue],
            ['label' => 'Deskripsi', 'bg' => $blue],
            ['label' => 'Quiz (%)', 'bg' => $blue], ['label' => 'Observasi (%)', 'bg' => $blue],
            ['label' => 'Unjuk Kerja (%)', 'bg' => $blue], ['label' => 'UTS (%)', 'bg' => $blue],
            ['label' => 'UAS (%)', 'bg' => $blue], ['label' => 'Tes Lisan (%)', 'bg' => $blue],
        ];

        $rows = [];
        foreach ($mkList as $mk) {
            foreach ($mk->cpmk as $c) {
                $p = $c->penilaian;
                $rows[] = [
                    $mk->kode_mk, $mk->semester, $c->cplProdi?->kode_cpl ?? '—', $c->kode_cpmk, $c->deskripsi,
                    $p?->bobot_quiz ?? '', $p?->bobot_observasi ?? '', $p?->bobot_unjuk_kerja ?? '',
                    $p?->bobot_uts ?? '', $p?->bobot_uas ?? '', $p?->bobot_tes_lisan ?? '',
                ];
            }
        }

        return $excel->download("bobot-penilaian-mk-{$kurikulum->kode}.xlsx", [
            'Bobot Penilaian MK' => ['headerRows' => [$header], 'rows' => $rows, 'colWidths' => [14,10,12,14,50,10,12,14,10,10,12]],
        ]);
    }

    public function exportRumusanNilai(Kurikulum $kurikulum, ExcelExportService $excel)
    {
        $mkList = $kurikulum->mataKuliah()
            ->with(['cpmk' => fn ($q) => $q->with(['cplProdi', 'penilaian', 'subCpmk' => fn ($q2) => $q2->orderBy('urutan')])->orderBy('kode_cpmk')])
            ->orderBy('semester')->orderBy('kode_mk')->get();

        $blue = '2563EB';
        $header = [
            ['label' => 'MK', 'bg' => $blue], ['label' => 'Semester', 'bg' => $blue],
            ['label' => 'CPL', 'bg' => $blue], ['label' => 'Kode CPMK', 'bg' => $blue],
            ['label' => 'Deskripsi CPMK', 'bg' => $blue], ['label' => 'Skor Maks CPMK', 'bg' => $blue],
        ];

        $rows = [];
        foreach ($mkList as $mk) {
            foreach ($mk->cpmk as $c) {
                $p = $c->penilaian;
                $skor = $p && !is_null($p->skor_maks) ? $p->skor_maks : $c->subCpmk->sum('bobot');
                $rows[] = [
                    $mk->kode_mk, $mk->semester, $c->cplProdi?->kode_cpl ?? '—',
                    $c->kode_cpmk, $c->deskripsi, $skor,
                ];
            }
        }

        return $excel->download("rumusan-nilai-mk-{$kurikulum->kode}.xlsx", [
            'Rumusan Nilai MK' => ['headerRows' => [$header], 'rows' => $rows, 'colWidths' => [14,10,12,14,50,14]],
        ]);
    }

    public function exportRumusanNilaiCpl(Kurikulum $kurikulum, ExcelExportService $excel)
    {
        $cplList = $kurikulum->cplProdi()
            ->orderByRaw("FIELD(kategori,'Sikap','Keterampilan Umum','Keterampilan Khusus','Pengetahuan')")
            ->orderBy('urutan')->get();

        $allCpmk = Cpmk::with(['mataKuliah', 'penilaian', 'subCpmk'])
            ->where('id_kurikulum', $kurikulum->id)
            ->whereIn('id_cpl', $cplList->pluck('id'))
            ->orderBy('kode_cpmk')->get();

        $blue = '1E40AF';
        $header = [
            ['label' => 'CPL', 'bg' => $blue], ['label' => 'MK', 'bg' => $blue],
            ['label' => 'Semester', 'bg' => $blue], ['label' => 'Kode CPMK', 'bg' => $blue],
            ['label' => 'Deskripsi', 'bg' => $blue], ['label' => 'Skor Maks', 'bg' => $blue],
        ];

        $rows = [];
        foreach ($cplList as $cpl) {
            foreach ($allCpmk->where('id_cpl', $cpl->id)->sortBy('kode_cpmk') as $c) {
                $p    = $c->penilaian;
                $skor = $p && !is_null($p->skor_maks) ? $p->skor_maks : $c->subCpmk->sum('bobot');
                $rows[] = [
                    $cpl->kode_cpl, $c->mataKuliah?->kode_mk ?? '—',
                    $c->mataKuliah?->semester ?? '—', $c->kode_cpmk, $c->deskripsi, $skor,
                ];
            }
        }

        return $excel->download("rumusan-nilai-cpl-{$kurikulum->kode}.xlsx", [
            'Rumusan Nilai CPL' => ['headerRows' => [$header], 'rows' => $rows, 'colWidths' => [12,14,10,14,50,12]],
        ]);
    }

    public function exportPetaSemester(Kurikulum $kurikulum, ExcelExportService $excel)
    {
        $cplList = $kurikulum->cplProdi()
            ->orderByRaw("FIELD(kategori,'Sikap','Keterampilan Umum','Keterampilan Khusus','Pengetahuan')")
            ->orderBy('urutan')->get();

        $allCpmk = Cpmk::with(['mataKuliah:id,kode_mk,nama_mk,semester'])
            ->where('id_kurikulum', $kurikulum->id)
            ->whereIn('id_cpl', $cplList->pluck('id'))
            ->orderBy('kode_cpmk')->get();

        $smts = range(1, 8);
        $blue = '3B82F6';

        $header = array_merge(
            [['label' => 'CPL', 'bg' => $blue], ['label' => 'Kode CPMK', 'bg' => $blue], ['label' => 'Deskripsi', 'bg' => $blue]],
            array_map(fn ($s) => ['label' => "Smt $s", 'bg' => $blue], $smts)
        );

        $rows = [];
        foreach ($cplList as $cpl) {
            $groups = $allCpmk->where('id_cpl', $cpl->id)->groupBy('kode_cpmk');
            foreach ($groups as $kode => $records) {
                $row = [$cpl->kode_cpl, $kode, $records->first()->deskripsi];
                foreach ($smts as $smt) {
                    $mks = $records->filter(fn ($r) => $r->mataKuliah && $r->mataKuliah->semester == $smt)
                        ->map(fn ($r) => $r->mataKuliah->kode_mk)->implode(', ');
                    $row[] = $mks;
                }
                $rows[] = $row;
            }
        }

        return $excel->download("peta-cpl-cpmk-semester-{$kurikulum->kode}.xlsx", [
            'CPL-CPMK-Semester' => ['headerRows' => [$header], 'rows' => $rows, 'colWidths' => [12,14,50,12,12,12,12,12,12,12,12]],
        ]);
    }
}
