<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\EnrollmentMk;
use App\Models\HasilCpl;
use App\Models\HasilCpmk;
use App\Models\KomponenAsesmen;
use App\Models\Kurikulum;
use App\Models\MataKuliah;
use App\Models\NilaiMahasiswa;
use App\Models\SemesterAkademik;
use App\Services\ExcelExportService;
use App\Services\GradeCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekapNilaiController extends Controller
{
    public function index(Kurikulum $kurikulum, Request $request)
    {
        $semesterList = SemesterAkademik::orderByDesc('id')->get();
        $semester = $this->resolveSemester($request, $semesterList);

        $mkBySemester = $kurikulum->mataKuliah()
            ->orderBy('semester')
            ->orderBy('kode_mk')
            ->get()
            ->groupBy('semester');

        return view('kurikulum.penilaian.rekap-nilai-index', compact(
            'kurikulum', 'mkBySemester', 'semester', 'semesterList',
        ));
    }

    public function show(Kurikulum $kurikulum, MataKuliah $mataKuliah, Request $request)
    {
        if ($mataKuliah->id_kurikulum !== $kurikulum->id) {
            abort(404);
        }

        $semesterList = SemesterAkademik::orderByDesc('id')->get();
        $semester = $this->resolveSemester($request, $semesterList);

        [$cplGroups, $lainnyaKomponen, $columnLayout] = $this->buildGridStructure($kurikulum, $mataKuliah, $semester);

        $mahasiswaList = collect();
        $nilaiMap = collect();
        $hasilCplMap = collect();

        if ($semester) {
            $mahasiswaList = EnrollmentMk::with('mahasiswa')
                ->where('id_mk', $mataKuliah->id)
                ->where('id_semester', $semester->id)
                ->where('status', 'aktif')
                ->get();

            $komponenIds = collect($columnLayout)
                ->where('type', 'komponen')
                ->pluck('komponen.id');

            $nilaiMap = NilaiMahasiswa::where('id_semester', $semester->id)
                ->whereIn('id_komponen', $komponenIds)
                ->get()
                ->groupBy('id_mahasiswa')
                ->map(fn ($rows) => $rows->keyBy('id_komponen')->map->nilai_mentah);

            $cplIds = collect($cplGroups)->pluck('cpl.id');

            $hasilCplMap = HasilCpl::where('id_kurikulum', $kurikulum->id)
                ->where('id_semester', $semester->id)
                ->whereIn('id_cpl', $cplIds)
                ->get()
                ->groupBy('id_mahasiswa')
                ->map(fn ($rows) => $rows->keyBy('id_cpl'));
        }

        [$mkTotalCol, $mkTotalMap] = $this->buildMkTotalMap($columnLayout, $mahasiswaList, $semester);

        $mkList = $kurikulum->mataKuliah()->orderBy('semester')->orderBy('kode_mk')->get();

        return view('kurikulum.penilaian.rekap-nilai-mk', compact(
            'kurikulum', 'mataKuliah', 'semester', 'semesterList', 'mkList',
            'cplGroups', 'lainnyaKomponen', 'columnLayout',
            'mahasiswaList', 'nilaiMap', 'hasilCplMap',
            'mkTotalCol', 'mkTotalMap',
        ));
    }

    public function store(Kurikulum $kurikulum, MataKuliah $mataKuliah, Request $request)
    {
        if ($mataKuliah->id_kurikulum !== $kurikulum->id) {
            abort(404);
        }

        $semesterList = SemesterAkademik::orderByDesc('id')->get();
        $semester = $this->resolveSemester($request, $semesterList);

        if (! $semester) {
            return back()->with('error', 'Semester akademik belum tersedia.');
        }

        $komponenList = KomponenAsesmen::where('id_mk', $mataKuliah->id)
            ->where('id_semester', $semester->id)
            ->get();

        $rules = ['nilai' => 'array'];
        foreach ($komponenList as $k) {
            $rules["nilai.*.{$k->id}"] = "nullable|numeric|min:0|max:{$k->skor_maks}";
        }
        $request->validate($rules);

        DB::transaction(function () use ($request, $semester) {
            foreach ($request->input('nilai', []) as $idMahasiswa => $komponenNilai) {
                foreach ($komponenNilai as $idKomponen => $nilaiMentah) {
                    if ($nilaiMentah === null || $nilaiMentah === '') {
                        continue;
                    }

                    NilaiMahasiswa::updateOrCreate(
                        [
                            'id_mahasiswa' => (int) $idMahasiswa,
                            'id_komponen'  => (int) $idKomponen,
                            'id_semester'  => $semester->id,
                        ],
                        ['nilai_mentah' => (float) $nilaiMentah]
                    );
                }
            }
        });

        app(GradeCalculationService::class)->recalcForMk($mataKuliah, $semester);

        return redirect()->route('kurikulum.penilaian.rekap-nilai.show', [$kurikulum, $mataKuliah, 'semester' => $semester->id])
            ->with('success', 'Nilai berhasil disimpan & capaian CPL diperbarui.');
    }

    public function export(Kurikulum $kurikulum, MataKuliah $mataKuliah, Request $request, ExcelExportService $excel)
    {
        if ($mataKuliah->id_kurikulum !== $kurikulum->id) {
            abort(404);
        }

        $semesterList = SemesterAkademik::orderByDesc('id')->get();
        $semester = $this->resolveSemester($request, $semesterList);

        [$cplGroups, $lainnyaKomponen, $columnLayout] = $this->buildGridStructure($kurikulum, $mataKuliah, $semester);

        $mahasiswaList = collect();
        $nilaiMap = collect();
        $hasilCplMap = collect();

        if ($semester) {
            $mahasiswaList = EnrollmentMk::with('mahasiswa')
                ->where('id_mk', $mataKuliah->id)
                ->where('id_semester', $semester->id)
                ->where('status', 'aktif')
                ->get();

            $komponenIds = collect($columnLayout)
                ->where('type', 'komponen')
                ->pluck('komponen.id');

            $nilaiMap = NilaiMahasiswa::where('id_semester', $semester->id)
                ->whereIn('id_komponen', $komponenIds)
                ->get()
                ->groupBy('id_mahasiswa')
                ->map(fn ($rows) => $rows->keyBy('id_komponen')->map->nilai_mentah);

            $cplIds = collect($cplGroups)->pluck('cpl.id');

            $hasilCplMap = HasilCpl::where('id_kurikulum', $kurikulum->id)
                ->where('id_semester', $semester->id)
                ->whereIn('id_cpl', $cplIds)
                ->get()
                ->groupBy('id_mahasiswa')
                ->map(fn ($rows) => $rows->keyBy('id_cpl'));
        }

        [$mkTotalCol, $mkTotalMap] = $this->buildMkTotalMap($columnLayout, $mahasiswaList, $semester);

        $hasGrid = ! empty($cplGroups) || $lainnyaKomponen->isNotEmpty();
        $headerRowCount = $hasGrid ? 4 : 1;

        $row1 = [['label' => 'Mahasiswa', 'rowspan' => $headerRowCount, 'bg' => 'F59E0B']];
        $row2 = [];
        $row3 = [];
        $row4 = [];

        foreach ($cplGroups as $group) {
            $row1[] = ['label' => $group['cpl']->kode_cpl, 'colspan' => $group['total_cols'], 'bg' => 'F59E0B'];
            $row1[] = ['label' => 'Capaian CPL', 'rowspan' => $headerRowCount, 'bg' => 'F59E0B'];

            foreach ($group['cpmk_groups'] as $cpmkGroup) {
                $row2[] = ['label' => $cpmkGroup['cpmk']->kode_cpmk, 'colspan' => $cpmkGroup['total_cols'], 'bg' => 'FCD34D'];

                foreach ($cpmkGroup['sub_groups'] as $subGroup) {
                    $row3[] = ['label' => $subGroup['sub']->kode_sub_cpmk, 'colspan' => $subGroup['komponen']->count(), 'bg' => 'FEF3C7'];

                    foreach ($subGroup['komponen'] as $k) {
                        $row4[] = ['label' => $k->nama_komponen, 'bg' => 'FFFBEB'];
                    }
                }
            }
        }

        if ($lainnyaKomponen->isNotEmpty()) {
            $row1[] = ['label' => 'Lainnya', 'colspan' => $lainnyaKomponen->count(), 'rowspan' => $headerRowCount - 1, 'bg' => 'F59E0B'];

            foreach ($lainnyaKomponen as $k) {
                $row4[] = ['label' => $k->nama_komponen, 'bg' => 'FFFBEB'];
            }
        }

        if ($mkTotalCol) {
            $row1[] = ['label' => 'Nilai Mata Kuliah ' . $mataKuliah->kode_mk . ' (' . $this->trimNumber($mkTotalCol['skor_maks']) . ')', 'rowspan' => $headerRowCount, 'bg' => 'F59E0B'];
        }

        $headerRows = $hasGrid ? [$row1, $row2, $row3, $row4] : [$row1];

        $dataRows = [];
        foreach ($mahasiswaList as $enrollment) {
            $mhs = $enrollment->mahasiswa;
            $row = [trim($mhs->name . ' (' . ($mhs->identifier ?? '-') . ')')];

            foreach ($columnLayout as $col) {
                if ($col['type'] === 'komponen') {
                    $k = $col['komponen'];
                    $row[] = $nilaiMap[$mhs->id][$k->id] ?? '';
                } elseif ($col['type'] === 'capaian') {
                    $cpl = $col['cpl'];
                    $hasil = $hasilCplMap[$mhs->id][$cpl->id] ?? null;
                    $row[] = $hasil
                        ? number_format((float) $hasil->nilai_cpl, 2) . ' (' . ($hasil->status_tercapai ? 'Tercapai' : 'Belum') . ')'
                        : '-';
                } else {
                    $val = $mkTotalMap[$mhs->id] ?? null;
                    $row[] = $val !== null ? number_format($val, 2) : '-';
                }
            }

            $dataRows[] = $row;
        }

        $colWidths = [28];
        foreach ($columnLayout as $col) {
            $colWidths[] = match ($col['type']) {
                'capaian'  => 18,
                'mk_total' => 16,
                default    => 12,
            };
        }

        $semesterLabel = $semester ? $semester->nama . ' ' . $semester->tahun_akademik : 'semester';

        return $excel->download("rekap-nilai-{$mataKuliah->kode_mk}-{$kurikulum->kode}.xlsx", [
            'Rekap Nilai' => [
                'headerRows' => $headerRows,
                'rows'       => $dataRows,
                'colWidths'  => $colWidths,
            ],
        ]);
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

    private function trimNumber(float $value): float|int
    {
        return $value == (int) $value ? (int) $value : $value;
    }

    /**
     * Hitung kolom "Nilai Mata Kuliah {kode_mk}" (total skor dari seluruh CPMK MK ini)
     * per mahasiswa, berdasarkan entri 'mk_total' terakhir pada $columnLayout.
     *
     * @return array{0: ?array, 1: array<int, ?float>}
     */
    private function buildMkTotalMap(array $columnLayout, $mahasiswaList, ?SemesterAkademik $semester): array
    {
        $mkTotalCol = collect($columnLayout)->firstWhere('type', 'mk_total');

        if (! $mkTotalCol || ! $semester) {
            return [$mkTotalCol, []];
        }

        $hasilCpmkMap = HasilCpmk::whereIn('id_cpmk', array_keys($mkTotalCol['cpmk_skor_maks']))
            ->where('id_semester', $semester->id)
            ->get()
            ->groupBy('id_mahasiswa')
            ->map(fn ($rows) => $rows->keyBy('id_cpmk'));

        $mkTotalMap = [];
        foreach ($mahasiswaList as $enrollment) {
            $mhsId = $enrollment->mahasiswa->id;
            $sum = null;

            foreach ($mkTotalCol['cpmk_skor_maks'] as $cpmkId => $skorMaks) {
                $hasil = $hasilCpmkMap[$mhsId][$cpmkId] ?? null;
                if ($hasil) {
                    $sum = ($sum ?? 0) + round((float) $hasil->nilai * $skorMaks / 100, 2);
                }
            }

            $mkTotalMap[$mhsId] = $sum === null ? null : round($sum, 2);
        }

        return [$mkTotalCol, $mkTotalMap];
    }

    /**
     * Build the 4-level header structure (CPL > CPMK > Sub-CPMK > Komponen) and
     * the flat column layout used to render both the input grid and the export sheet.
     *
     * @return array{0: array<int, array{cpl: \App\Models\CplProdi, total_cols: int, cpmk_groups: array}>, 1: \Illuminate\Support\Collection, 2: array}
     */
    private function buildGridStructure(Kurikulum $kurikulum, MataKuliah $mataKuliah, ?SemesterAkademik $semester): array
    {
        if (! $semester) {
            return [[], collect(), []];
        }

        $cplList = $kurikulum->cplProdi()->orderBy('urutan')->get();

        $cpmkList = $mataKuliah->cpmk()
            ->with(['subCpmk' => fn ($q) => $q->orderBy('urutan'), 'cplProdi', 'penilaian'])
            ->orderBy('urutan')
            ->get();

        $komponenList = KomponenAsesmen::where('id_mk', $mataKuliah->id)
            ->where('id_semester', $semester->id)
            ->get();

        $cpmkByCpl = $cpmkList->groupBy('id_cpl');

        $cplGroups = [];
        $columnLayout = [];
        $mkCpmkSkorMaks = []; // id_cpmk => skor_maks, untuk kolom "Nilai Mata Kuliah"
        $mkTotalSkorMaks = 0.0;

        foreach ($cplList as $cpl) {
            $cpmksForCpl = $cpmkByCpl->get($cpl->id, collect());
            if ($cpmksForCpl->isEmpty()) {
                continue;
            }

            $cpmkGroups = [];
            $cplTotalCols = 0;

            foreach ($cpmksForCpl as $cpmk) {
                $subGroups = [];
                $cpmkTotalCols = 0;

                foreach ($cpmk->subCpmk as $sub) {
                    $komponenForSub = $komponenList->where('id_sub_cpmk', $sub->id)->values();
                    if ($komponenForSub->isEmpty()) {
                        continue;
                    }

                    $subGroups[] = [
                        'sub'      => $sub,
                        'komponen' => $komponenForSub,
                    ];
                    $cpmkTotalCols += $komponenForSub->count();

                    foreach ($komponenForSub as $k) {
                        $columnLayout[] = ['type' => 'komponen', 'komponen' => $k];
                    }
                }

                if (empty($subGroups)) {
                    continue;
                }

                $cpmkGroups[] = [
                    'cpmk'       => $cpmk,
                    'total_cols' => $cpmkTotalCols,
                    'sub_groups' => $subGroups,
                ];
                $cplTotalCols += $cpmkTotalCols;

                $p = $cpmk->penilaian;
                $skorMaks = $p && ! is_null($p->skor_maks) ? (float) $p->skor_maks : (float) $cpmk->subCpmk->sum('bobot');
                $mkCpmkSkorMaks[$cpmk->id] = $skorMaks;
                $mkTotalSkorMaks += $skorMaks;
            }

            if (empty($cpmkGroups)) {
                continue;
            }

            $cplGroups[] = [
                'cpl'         => $cpl,
                'total_cols'  => $cplTotalCols,
                'cpmk_groups' => $cpmkGroups,
            ];

            $columnLayout[] = ['type' => 'capaian', 'cpl' => $cpl];
        }

        $lainnyaKomponen = $komponenList->whereNull('id_sub_cpmk')->values();
        foreach ($lainnyaKomponen as $k) {
            $columnLayout[] = ['type' => 'komponen', 'komponen' => $k];
        }

        if (! empty($mkCpmkSkorMaks)) {
            $columnLayout[] = [
                'type'           => 'mk_total',
                'mk'             => $mataKuliah,
                'skor_maks'      => $mkTotalSkorMaks,
                'cpmk_skor_maks' => $mkCpmkSkorMaks,
            ];
        }

        return [$cplGroups, $lainnyaKomponen, $columnLayout];
    }
}
