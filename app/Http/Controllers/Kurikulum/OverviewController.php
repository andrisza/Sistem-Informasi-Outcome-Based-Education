<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\Cpmk;
use App\Models\HasilPl;
use App\Models\KomponenAsesmen;
use App\Models\Kurikulum;
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

        foreach ($mkCplRows as $row) {
            if (!isset($mkById[$row->id_mk])) continue;
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

        return view('kurikulum.overview.pemenuhan-cpl', compact(
            'kurikulum', 'cplList', 'pemenuhan', 'peta', 'semesterRange'
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

        if ($cplList->isEmpty()) {
            return view('kurikulum.overview.cpl-cpmk-mk', ['kurikulum' => $kurikulum, 'tableData' => []]);
        }

        // 2. Load SEMUA CPMK milik kurikulum ini beserta MK-nya
        //    Menggunakan eager loading + fresh query tanpa cache
        $allCpmk = Cpmk::with(['mataKuliah:id,kode_mk,nama_mk,semester,sks_teori,sks_praktikum'])
            ->where('id_kurikulum', $kurikulum->id)
            ->whereIn('id_cpl', $cplList->pluck('id'))
            ->orderBy('kode_cpmk')
            ->orderBy('id')
            ->get();

        // 3. Build tableData: CPL → [kode_cpmk → {deskripsi, mk_list}]
        $tableData = [];
        foreach ($cplList as $cpl) {
            // Filter CPMK milik CPL ini
            $cpmkForCpl = $allCpmk->where('id_cpl', $cpl->id);

            $cpmkGroups = [];
            foreach ($cpmkForCpl->groupBy('kode_cpmk') as $kode => $records) {
                // Kumpulkan semua MK unik untuk kode CPMK ini
                $mks = $records
                    ->map(fn($r) => $r->mataKuliah)
                    ->filter()                               // hapus null (MK sudah dihapus)
                    ->unique('id')                           // deduplikasi
                    ->sortBy([['semester','asc'],['kode_mk','asc']])
                    ->values();

                $cpmkGroups[] = [
                    'kode'      => $kode,
                    'deskripsi' => $records->first()->deskripsi,
                    'mk_list'   => $mks,
                ];
            }

            // Urutkan CPMK groups by kode (CPMK011 < CPMK012 < ...)
            usort($cpmkGroups, fn($a, $b) => strcmp($a['kode'], $b['kode']));

            $tableData[] = [
                'cpl'       => $cpl,
                'cpmk_rows' => $cpmkGroups,
                'row_count' => max(count($cpmkGroups), 1),
            ];
        }

        return view('kurikulum.overview.cpl-cpmk-mk', compact('kurikulum', 'tableData'));
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
}
