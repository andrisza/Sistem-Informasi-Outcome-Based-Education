<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\EnrollmentMk;
use App\Models\HasilCpl;
use App\Models\HasilCpmk;
use App\Models\KomponenAsesmen;
use App\Models\Kurikulum;
use App\Models\MataKuliah;
use App\Models\SemesterAkademik;
use App\Models\User;
use App\Services\ExcelExportService;
use App\Services\GradeCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class RekapCplController extends Controller
{
    public function index(Kurikulum $kurikulum, Request $request)
    {
        $semesterList = SemesterAkademik::orderByDesc('id')->get();
        $semester = $this->resolveSemester($request, $semesterList);
        $filters = $this->resolveFilters($request);
        $filterOptions = $this->resolveFilterOptions($kurikulum, $semester);

        [$mkGroups, $cplAggGroups, $columnLayout, $mahasiswaList, $valueGrid, $totalRow] =
            $this->buildGrid($kurikulum, $semester, null, $filters);

        return view('kurikulum.penilaian.rekap-cpl', compact(
            'kurikulum', 'semester', 'semesterList',
            'mkGroups', 'cplAggGroups', 'columnLayout',
            'mahasiswaList', 'valueGrid', 'totalRow',
            'filters', 'filterOptions',
        ));
    }

    public function export(Kurikulum $kurikulum, Request $request, ExcelExportService $excel)
    {
        $semesterList = SemesterAkademik::orderByDesc('id')->get();
        $semester = $this->resolveSemester($request, $semesterList);
        $filters = $this->resolveFilters($request);

        [$mkGroups, $cplAggGroups, $columnLayout, $mahasiswaList, $valueGrid, $totalRow] =
            $this->buildGrid($kurikulum, $semester, null, $filters);

        $sheet = $this->buildSheetData($mkGroups, $cplAggGroups, $columnLayout, $mahasiswaList, $valueGrid, $totalRow);

        return $excel->download("rekap-capaian-cpl-{$kurikulum->kode}.xlsx", [
            'Tabel L' => $sheet,
        ]);
    }

    /**
     * Tabel K — Proses Penilaian & Evaluasi CPL: drill-down per satu CPL Prodi,
     * menampilkan MK-MK yang berkontribusi pada CPL tersebut beserta agregasi capaiannya.
     */
    public function proses(Kurikulum $kurikulum, Request $request)
    {
        $semesterList = SemesterAkademik::orderByDesc('id')->get();
        $semester = $this->resolveSemester($request, $semesterList);
        $filters = $this->resolveFilters($request);
        $filterOptions = $this->resolveFilterOptions($kurikulum, $semester);

        [$cplOptions, $selectedCpl] = $this->resolveCplSelection($kurikulum, $semester, $request);

        [$mkGroups, $cplAggGroups, $columnLayout, $mahasiswaList, $valueGrid, $totalRow] = $selectedCpl
            ? $this->buildGrid($kurikulum, $semester, $selectedCpl->id, $filters)
            : [[], [], [], collect(), [], []];

        return view('kurikulum.penilaian.rekap-cpl-proses', compact(
            'kurikulum', 'semester', 'semesterList',
            'cplOptions', 'selectedCpl',
            'mkGroups', 'cplAggGroups', 'columnLayout',
            'mahasiswaList', 'valueGrid', 'totalRow',
            'filters', 'filterOptions',
        ));
    }

    public function prosesExport(Kurikulum $kurikulum, Request $request, ExcelExportService $excel)
    {
        $semesterList = SemesterAkademik::orderByDesc('id')->get();
        $semester = $this->resolveSemester($request, $semesterList);
        $filters = $this->resolveFilters($request);

        [, $selectedCpl] = $this->resolveCplSelection($kurikulum, $semester, $request);

        abort_if(! $selectedCpl, 404);

        [$mkGroups, $cplAggGroups, $columnLayout, $mahasiswaList, $valueGrid, $totalRow] =
            $this->buildGrid($kurikulum, $semester, $selectedCpl->id, $filters);

        $sheet = $this->buildSheetData($mkGroups, $cplAggGroups, $columnLayout, $mahasiswaList, $valueGrid, $totalRow);

        return $excel->download("proses-evaluasi-cpl-{$selectedCpl->kode_cpl}-{$kurikulum->kode}.xlsx", [
            "Tabel K - {$selectedCpl->kode_cpl}" => $sheet,
        ]);
    }

    public function import(Kurikulum $kurikulum, Request $request)
    {
        $request->validate([
            'semester' => 'required|integer|exists:semester_akademik,id',
            'file'     => 'required|file|mimes:xlsx,xls|max:10240',
        ]);

        $semester = SemesterAkademik::find($request->semester);

        [, , $columnLayout, , , ] = $this->buildGrid($kurikulum, $semester, null);

        $result = $this->performImport($columnLayout, $semester, $request);

        $filters = $this->resolveFilters($request);

        return redirect()->route('kurikulum.penilaian.rekap-cpl', array_merge(
            ['kurikulum' => $kurikulum, 'semester' => $semester->id],
            array_filter($filters)
        ))->with($result['status'], $result['message']);
    }

    /**
     * Import Excel untuk Tabel K — sama seperti import() tetapi $columnLayout
     * difilter ke 1 CPL (sesuai template hasil prosesExport()).
     */
    public function prosesImport(Kurikulum $kurikulum, Request $request)
    {
        $request->validate([
            'semester' => 'required|integer|exists:semester_akademik,id',
            'cpl'      => 'required|integer|exists:cpl_prodi,id',
            'file'     => 'required|file|mimes:xlsx,xls|max:10240',
        ]);

        $semester = SemesterAkademik::find($request->semester);

        [, , $columnLayout, , , ] = $this->buildGrid($kurikulum, $semester, (int) $request->cpl);

        $result = $this->performImport($columnLayout, $semester, $request);

        $filters = $this->resolveFilters($request);

        return redirect()->route('kurikulum.penilaian.evaluasi-cpl', array_merge(
            ['kurikulum' => $kurikulum, 'semester' => $semester->id, 'cpl' => $request->cpl],
            array_filter($filters)
        ))->with($result['status'], $result['message']);
    }

    /**
     * Baca file Excel sesuai struktur $columnLayout. Kolom bertipe 'cpmk' berisi nilai
     * capaian CPMK (skala skor_maks kolom tersebut) hasil evaluasi — nilai ini ditulis
     * langsung ke HasilCpmk (skala 0-100), lalu HasilCpl/HasilPl untuk CPL terdampak
     * dihitung ulang dari HasilCpmk. Kolom mk_total/cpl_nilai/cpl_capaian dilewati
     * karena nilainya turunan/terhitung otomatis.
     *
     * @return array{status: string, message: string}
     */
    private function performImport(array $columnLayout, SemesterAkademik $semester, Request $request): array
    {
        $cpmkColumns = [];
        foreach ($columnLayout as $i => $col) {
            if ($col['type'] === 'cpmk') {
                $cpmkColumns[$i] = $col;
            }
        }

        if (empty($cpmkColumns)) {
            return ['status' => 'error', 'message' => 'Tidak ada data CPMK untuk semester ini.'];
        }

        $spreadsheet = IOFactory::load($request->file('file')->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, false);

        $updated = 0;
        $studentsTouched = 0;
        $notFound = [];
        $affectedCpl = collect(); // key 'mhsId-cplId' => ['mhs_id'=>, 'cpl_id'=>, 'kurikulum_id'=>]

        DB::transaction(function () use (
            $rows, $cpmkColumns, $semester, &$updated, &$studentsTouched, &$notFound, &$affectedCpl
        ) {
            foreach ($rows as $row) {
                $label = trim((string) ($row[0] ?? ''));
                if ($label === '' || ! preg_match('/\(([^)]+)\)\s*$/', $label, $m)) {
                    continue;
                }

                $identifier = trim($m[1]);
                $mhs = User::where('identifier', $identifier)->first();
                if (! $mhs) {
                    $notFound[] = $identifier;
                    continue;
                }

                $rowChanged = false;

                foreach ($cpmkColumns as $colIdx => $col) {
                    $skorMaks = (float) $col['skor_maks'];
                    if ($skorMaks <= 0) {
                        continue;
                    }

                    $cellValue = $row[$colIdx + 1] ?? null; // +1 karena kolom 0 = nama mahasiswa
                    if ($cellValue === null || $cellValue === '' || ! is_numeric($cellValue)) {
                        continue;
                    }

                    $nilaiCpmk = round(((float) $cellValue) / $skorMaks * 100, 2);
                    $nilaiCpmk = max(0, min(100, $nilaiCpmk));

                    HasilCpmk::updateOrCreate(
                        [
                            'id_mahasiswa' => $mhs->id,
                            'id_cpmk'      => $col['cpmk']->id,
                            'id_semester'  => $semester->id,
                        ],
                        ['nilai' => $nilaiCpmk]
                    );

                    $updated++;
                    $rowChanged = true;

                    if ($col['cpmk']->id_cpl) {
                        $affectedCpl->put($mhs->id . '-' . $col['cpmk']->id_cpl, [
                            'mhs_id'       => $mhs->id,
                            'cpl_id'       => $col['cpmk']->id_cpl,
                            'kurikulum_id' => $col['cpmk']->id_kurikulum,
                        ]);
                    }
                }

                if ($rowChanged) {
                    $studentsTouched++;
                }
            }
        });

        $gradeService = app(GradeCalculationService::class);
        foreach ($affectedCpl as $a) {
            $gradeService->recalcCplForMahasiswa($a['mhs_id'], $a['cpl_id'], $a['kurikulum_id'], $semester->id);
        }

        $cplCount = $affectedCpl->pluck('cpl_id')->unique()->count();
        $message = "Import selesai: {$updated} nilai CPMK diperbarui untuk {$studentsTouched} mahasiswa, capaian {$cplCount} CPL dihitung ulang.";
        if (! empty($notFound)) {
            $message .= ' NIM tidak ditemukan: ' . implode(', ', array_unique($notFound)) . '.';
        }

        return ['status' => 'success', 'message' => $message];
    }

    // ── Helpers ────────────────────────────────────────────────

    private function resolveSemester(Request $request, $semesterList): ?SemesterAkademik
    {
        if ($request->filled('semester')) {
            $semester = $semesterList->firstWhere('id', (int) $request->semester);
            if ($semester) {
                return $semester;
            }
        }

        return $semesterList->firstWhere('is_aktif', true) ?? $semesterList->first();
    }

    /**
     * Resolve daftar CPL Prodi yang punya data assessment pada semester ini ($cplOptions),
     * dan CPL terpilih (dari query string `cpl`, atau default CPL pertama yang punya data).
     *
     * @return array{0: \Illuminate\Support\Collection, 1: ?\App\Models\CplProdi}
     */
    private function resolveCplSelection(Kurikulum $kurikulum, ?SemesterAkademik $semester, Request $request): array
    {
        [, $allCplAggGroups] = $this->buildGrid($kurikulum, $semester, null);
        $cplOptions = collect($allCplAggGroups)->pluck('cpl')->values();

        $selectedCpl = null;
        if ($request->filled('cpl')) {
            $selectedCpl = $cplOptions->firstWhere('id', (int) $request->cpl);
        }

        return [$cplOptions, $selectedCpl ?? $cplOptions->first()];
    }

    private function trimNumber(float $value): float|int
    {
        return $value == (int) $value ? (int) $value : $value;
    }

    /**
     * Ambil filter mahasiswa (tahun angkatan, kelas, jurusan/prodi) dari query string.
     *
     * @return array{tahun_masuk: ?int, kelas: ?string, program_studi: ?string}
     */
    private function resolveFilters(Request $request): array
    {
        return [
            'tahun_masuk'   => $request->filled('tahun_masuk') ? (int) $request->tahun_masuk : null,
            'kelas'         => $request->filled('kelas') ? trim($request->kelas) : null,
            'program_studi' => $request->filled('program_studi') ? trim($request->program_studi) : null,
        ];
    }

    /**
     * Daftar pilihan filter (tahun angkatan, kelas, jurusan/prodi) berdasarkan mahasiswa
     * yang punya enrollment aktif pada MK-MK kurikulum ini di semester terpilih.
     *
     * @return array{tahun_masuk: array, kelas: array, program_studi: array}
     */
    private function resolveFilterOptions(Kurikulum $kurikulum, ?SemesterAkademik $semester): array
    {
        $empty = ['tahun_masuk' => [], 'kelas' => [], 'program_studi' => []];

        if (! $semester) {
            return $empty;
        }

        $mkIds = $this->resolveMkIds($kurikulum, $semester);

        $mahasiswaIds = EnrollmentMk::whereIn('id_mk', $mkIds)
            ->where('id_semester', $semester->id)
            ->where('status', 'aktif')
            ->pluck('id_mahasiswa')
            ->unique();

        $mahasiswa = User::whereIn('id', $mahasiswaIds)->get(['tahun_masuk', 'kelas', 'program_studi']);

        return [
            'tahun_masuk'   => $mahasiswa->pluck('tahun_masuk')->filter()->unique()->sort()->values()->all(),
            'kelas'         => $mahasiswa->pluck('kelas')->filter()->unique()->sort()->values()->all(),
            'program_studi' => $mahasiswa->pluck('program_studi')->filter()->unique()->sort()->values()->all(),
        ];
    }

    /**
     * ID Mata Kuliah dalam kurikulum ini yang punya Komponen Asesmen (terhubung ke Sub-CPMK)
     * pada semester terpilih.
     */
    private function resolveMkIds(Kurikulum $kurikulum, SemesterAkademik $semester): \Illuminate\Support\Collection
    {
        return KomponenAsesmen::where('id_semester', $semester->id)
            ->whereIn('id_mk', $kurikulum->mataKuliah()->pluck('id'))
            ->whereNotNull('id_sub_cpmk')
            ->pluck('id_mk')
            ->unique();
    }

    /**
     * Bangun struktur grid CPL: MK -> CPL -> CPMK, kolom "Nilai Mata Kuliah" per MK,
     * dan kolom agregat "Nilai CPLxx dari [MK...]" + "Capaian CPLxx" di akhir.
     *
     * Jika $onlyCplId diisi, grid difilter agar hanya berisi CPMK/MK yang berkontribusi
     * pada CPL tersebut, dan hanya 1 kolom agregat (Tabel K — proses per CPL).
     * Jika null, seluruh CPL Prodi diikutkan (Tabel L — assessment lintas CPL & MK).
     *
     * Mengembalikan juga $valueGrid[id_mahasiswa][indexKolom] => nilai (float|null)
     * dan $totalRow[indexKolom] => skor maksimal kolom tersebut.
     *
     * @return array{0: array, 1: array, 2: array, 3: \Illuminate\Support\Collection, 4: array, 5: array}
     */
    private function buildGrid(Kurikulum $kurikulum, ?SemesterAkademik $semester, ?int $onlyCplId = null, array $filters = []): array
    {
        if (! $semester) {
            return [[], [], [], collect(), [], []];
        }

        $komponenAll = KomponenAsesmen::where('id_semester', $semester->id)
            ->whereIn('id_mk', $kurikulum->mataKuliah()->pluck('id'))
            ->whereNotNull('id_sub_cpmk')
            ->get();

        $mkIds = $komponenAll->pluck('id_mk')->unique();
        $mkList = MataKuliah::whereIn('id', $mkIds)->orderBy('semester')->orderBy('kode_mk')->get();
        $komponenBySubCpmk = $komponenAll->groupBy('id_sub_cpmk');

        $cplList = $kurikulum->cplProdi()
            ->orderByRaw("FIELD(kategori,'Sikap','Keterampilan Umum','Keterampilan Khusus','Pengetahuan')")
            ->orderBy('urutan')
            ->get()
            ->keyBy('id');

        if ($onlyCplId !== null) {
            $cplList = $cplList->only([$onlyCplId]);
        }

        $mkGroups = [];
        $columnLayout = [];
        $cplCpmkCols = []; // id_cpl => array of columnLayout index

        foreach ($mkList as $mk) {
            $cpmkList = $mk->cpmk()
                ->with(['subCpmk', 'cplProdi', 'penilaian'])
                ->orderBy('urutan')
                ->get();

            $cplGroups = []; // id_cpl => ['cpl'=>, 'cpmks'=>[], 'total_skor_maks'=>]
            $mkTotalSkorMaks = 0.0;
            $mkCpmkColIdxs = [];

            foreach ($cpmkList as $cpmk) {
                $hasKomponen = $cpmk->subCpmk->contains(fn ($sub) => $komponenBySubCpmk->has($sub->id));
                if (! $hasKomponen) {
                    continue;
                }

                $cplId = $cpmk->id_cpl;

                if ($onlyCplId !== null && $cplId !== $onlyCplId) {
                    continue;
                }

                $p = $cpmk->penilaian;
                $skorMaks = $p && ! is_null($p->skor_maks) ? (float) $p->skor_maks : (float) $cpmk->subCpmk->sum('bobot');

                if (! isset($cplGroups[$cplId])) {
                    $cplGroups[$cplId] = [
                        'cpl'             => $cpmk->cplProdi,
                        'cpmks'           => [],
                        'total_skor_maks' => 0.0,
                    ];
                }

                $columnLayout[] = ['type' => 'cpmk', 'cpmk' => $cpmk, 'mk' => $mk, 'skor_maks' => $skorMaks];
                $colIdx = count($columnLayout) - 1;

                $cplGroups[$cplId]['cpmks'][] = ['cpmk' => $cpmk, 'skor_maks' => $skorMaks];
                $cplGroups[$cplId]['total_skor_maks'] += $skorMaks;
                $mkTotalSkorMaks += $skorMaks;
                $mkCpmkColIdxs[] = $colIdx;

                if ($cplId) {
                    $cplCpmkCols[$cplId][] = $colIdx;
                }
            }

            if (empty($cplGroups)) {
                continue;
            }

            $columnLayout[] = [
                'type'      => 'mk_total',
                'mk'        => $mk,
                'skor_maks' => $mkTotalSkorMaks,
                'cpmk_cols' => $mkCpmkColIdxs,
            ];

            $mkGroups[] = [
                'mk'              => $mk,
                'cpl_groups'      => array_values($cplGroups),
                'total_skor_maks' => $mkTotalSkorMaks,
            ];
        }

        $cplAggGroups = [];
        foreach ($cplList as $cplId => $cpl) {
            if (empty($cplCpmkCols[$cplId])) {
                continue;
            }

            $cpmkColIdxs = $cplCpmkCols[$cplId];
            $totalSkorMaks = array_sum(array_map(fn ($i) => $columnLayout[$i]['skor_maks'], $cpmkColIdxs));
            $mkCodes = collect($cpmkColIdxs)->map(fn ($i) => $columnLayout[$i]['mk']->kode_mk)->unique()->values()->all();

            $columnLayout[] = [
                'type'      => 'cpl_nilai',
                'cpl'       => $cpl,
                'cpmk_cols' => $cpmkColIdxs,
                'skor_maks' => $totalSkorMaks,
                'mk_codes'  => $mkCodes,
            ];
            $columnLayout[] = ['type' => 'cpl_capaian', 'cpl' => $cpl];

            $cplAggGroups[] = [
                'cpl'             => $cpl,
                'total_skor_maks' => $totalSkorMaks,
                'mk_codes'        => $mkCodes,
            ];
        }

        $mahasiswaList = EnrollmentMk::with('mahasiswa')
            ->whereIn('id_mk', $mkIds)
            ->where('id_semester', $semester->id)
            ->where('status', 'aktif')
            ->whereHas('mahasiswa', function ($q) use ($filters) {
                if (! empty($filters['tahun_masuk'])) {
                    $q->where('tahun_masuk', $filters['tahun_masuk']);
                }
                if (! empty($filters['kelas'])) {
                    $q->where('kelas', $filters['kelas']);
                }
                if (! empty($filters['program_studi'])) {
                    $q->where('program_studi', $filters['program_studi']);
                }
            })
            ->get()
            ->unique('id_mahasiswa')
            ->sortBy(fn ($e) => $e->mahasiswa->name)
            ->values();

        $cpmkIds = collect($columnLayout)->where('type', 'cpmk')->pluck('cpmk.id');
        $hasilCpmkMap = HasilCpmk::whereIn('id_cpmk', $cpmkIds)
            ->where('id_semester', $semester->id)
            ->get()
            ->groupBy('id_mahasiswa')
            ->map(fn ($rows) => $rows->keyBy('id_cpmk'));

        $cplIds = collect($cplAggGroups)->pluck('cpl.id');
        $hasilCplMap = HasilCpl::where('id_kurikulum', $kurikulum->id)
            ->where('id_semester', $semester->id)
            ->whereIn('id_cpl', $cplIds)
            ->get()
            ->groupBy('id_mahasiswa')
            ->map(fn ($rows) => $rows->keyBy('id_cpl'));

        // ── Hitung value grid [id_mahasiswa][indexKolom] => nilai ──
        $valueGrid = [];
        foreach ($mahasiswaList as $enrollment) {
            $mhsId = $enrollment->mahasiswa->id;
            $rowVals = [];

            foreach ($columnLayout as $i => $col) {
                if ($col['type'] === 'cpmk') {
                    $hasil = $hasilCpmkMap[$mhsId][$col['cpmk']->id] ?? null;
                    $rowVals[$i] = $hasil ? round((float) $hasil->nilai * (float) $col['skor_maks'] / 100, 2) : null;
                } elseif ($col['type'] === 'cpl_capaian') {
                    $hasil = $hasilCplMap[$mhsId][$col['cpl']->id] ?? null;
                    $rowVals[$i] = $hasil ? round((float) $hasil->nilai_cpl, 2) : null;
                }
            }

            foreach ($columnLayout as $i => $col) {
                if ($col['type'] === 'mk_total' || $col['type'] === 'cpl_nilai') {
                    $sum = null;
                    foreach ($col['cpmk_cols'] as $idx) {
                        if (($rowVals[$idx] ?? null) !== null) {
                            $sum = ($sum ?? 0) + $rowVals[$idx];
                        }
                    }
                    $rowVals[$i] = $sum === null ? null : round($sum, 2);
                }
            }

            $valueGrid[$mhsId] = $rowVals;
        }

        // ── Baris "Nilai Total" (skor maksimal tiap kolom) ──
        $totalRow = [];
        foreach ($columnLayout as $i => $col) {
            $totalRow[$i] = match ($col['type']) {
                'cpmk', 'mk_total', 'cpl_nilai' => $this->trimNumber($col['skor_maks']),
                'cpl_capaian' => 100,
                default => null,
            };
        }

        return [$mkGroups, $cplAggGroups, $columnLayout, $mahasiswaList, $valueGrid, $totalRow];
    }

    /**
     * Bangun struktur sheet (headerRows/rows/colWidths) untuk ExcelExportService
     * dari hasil buildGrid() — dipakai oleh export() (Tabel L) dan prosesExport() (Tabel K).
     */
    private function buildSheetData(array $mkGroups, array $cplAggGroups, array $columnLayout, $mahasiswaList, array $valueGrid, array $totalRow): array
    {
        $hasGrid = ! empty($mkGroups) || ! empty($cplAggGroups);
        $headerRowCount = $hasGrid ? 3 : 1;

        $row1 = [['label' => 'Nama Mahasiswa', 'rowspan' => $headerRowCount, 'bg' => 'F59E0B']];
        $row2 = [];
        $row3 = [];

        foreach ($mkGroups as $mkGroup) {
            $mkColCount = array_sum(array_map(fn ($g) => count($g['cpmks']), $mkGroup['cpl_groups'])) + 1;
            $row1[] = ['label' => $mkGroup['mk']->kode_mk, 'colspan' => $mkColCount, 'bg' => 'F59E0B'];

            foreach ($mkGroup['cpl_groups'] as $cplGroup) {
                $row2[] = ['label' => $cplGroup['cpl']?->kode_cpl ?? 'Lainnya', 'colspan' => count($cplGroup['cpmks']), 'bg' => 'FCD34D'];

                foreach ($cplGroup['cpmks'] as $c) {
                    $row3[] = ['label' => $c['cpmk']->kode_cpmk . ' (' . $this->trimNumber($c['skor_maks']) . ')', 'bg' => 'FEF3C7'];
                }
            }

            $row2[] = ['label' => 'Nilai Mata Kuliah ' . $mkGroup['mk']->kode_mk . ' (' . $this->trimNumber($mkGroup['total_skor_maks']) . ')', 'rowspan' => 2, 'bg' => 'FCD34D'];
        }

        foreach ($cplAggGroups as $aggGroup) {
            $mkCodes = implode(' & ', $aggGroup['mk_codes']);
            $row1[] = ['label' => "Nilai {$aggGroup['cpl']->kode_cpl} dari {$mkCodes} (" . $this->trimNumber($aggGroup['total_skor_maks']) . ')', 'rowspan' => $headerRowCount, 'bg' => 'F59E0B'];
            $row1[] = ['label' => "Capaian {$aggGroup['cpl']->kode_cpl} (Skor/" . $this->trimNumber($aggGroup['total_skor_maks']) . '*100%)', 'rowspan' => $headerRowCount, 'bg' => 'F59E0B'];
        }

        $headerRows = $hasGrid ? [$row1, $row2, $row3] : [$row1];

        $dataRows = [];

        // Baris "Nilai Total" = skor maksimal tiap kolom
        $totalRowOut = ['Nilai Total'];
        foreach ($columnLayout as $i => $col) {
            $totalRowOut[] = $totalRow[$i] ?? '';
        }
        $dataRows[] = $totalRowOut;

        foreach ($mahasiswaList as $enrollment) {
            $mhs = $enrollment->mahasiswa;
            $row = [trim($mhs->name . ' (' . ($mhs->identifier ?? '-') . ')')];

            foreach ($columnLayout as $i => $col) {
                $row[] = $valueGrid[$mhs->id][$i] ?? '';
            }

            $dataRows[] = $row;
        }

        $colWidths = [28];
        foreach ($columnLayout as $col) {
            $colWidths[] = $col['type'] === 'cpl_capaian' ? 16 : 12;
        }

        return [
            'headerRows' => $headerRows,
            'rows'       => $dataRows,
            'colWidths'  => $colWidths,
        ];
    }
}
