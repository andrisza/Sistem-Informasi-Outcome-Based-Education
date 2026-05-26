<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\Cpmk;
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
     */
    public function pemenuhanCpl(Kurikulum $kurikulum)
    {
        $cplList = $kurikulum->cplProdi()->orderBy('urutan')->get();
        $mkList  = $kurikulum->mataKuliah()->orderBy('semester')->orderBy('kode_mk')->get();

        // {cpl_id => [{mk, cpmk_count, bk_codes}]}
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

        $mkById = $mkList->keyBy('id');

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

        return view('kurikulum.overview.pemenuhan-cpl', compact('kurikulum', 'cplList', 'pemenuhan'));
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
}
