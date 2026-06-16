<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\Kurikulum;

class GeneratePdfController extends Controller
{
    /**
     * Tampilkan halaman print-optimized dokumen kurikulum lengkap.
     * Pengguna dapat mencetak atau menyimpan sebagai PDF via browser.
     */
    public function dokumenKurikulum(Kurikulum $kurikulum)
    {
        $kurikulum->load([
            'pl',
            'cplProdi',
            'bahanKajian',
            'mataKuliah' => fn ($q) => $q->orderBy('semester')->orderBy('kode_mk'),
        ]);

        $mkBySemester = $kurikulum->mataKuliah->groupBy('semester');

        // CPMK (dengan Sub-CPMK dan penilaian) per MK
        $cpmkByMk = \App\Models\Cpmk::where('id_kurikulum', $kurikulum->id)
            ->with(['cplProdi', 'subCpmk' => fn ($q) => $q->orderBy('urutan'), 'penilaian'])
            ->orderBy('kode_cpmk')
            ->get()
            ->groupBy('id_mk');

        // Pivot PL ↔ CPL: id_pl → [id_cpl, ...]
        $pivotPlCpl = \Illuminate\Support\Facades\DB::table('pivot_pl_cpl')
            ->whereIn('id_pl', $kurikulum->pl->pluck('id'))
            ->get()
            ->groupBy('id_pl')
            ->map(fn ($rows) => $rows->pluck('id_cpl')->toArray());

        // Pivot CPL ↔ BK: id_cpl → [id_bk, ...]
        $pivotCplBk = \Illuminate\Support\Facades\DB::table('pivot_cpl_bk')
            ->whereIn('id_cpl', $kurikulum->cplProdi->pluck('id'))
            ->get()
            ->groupBy('id_cpl')
            ->map(fn ($rows) => $rows->pluck('id_bk')->toArray());

        // Pivot MK ↔ CPL: id_mk → [id_cpl, ...]
        $pivotMkCpl = \Illuminate\Support\Facades\DB::table('pivot_mk_cpl')
            ->whereIn('id_mk', $kurikulum->mataKuliah->pluck('id'))
            ->get()
            ->groupBy('id_mk')
            ->map(fn ($rows) => $rows->pluck('id_cpl')->toArray());

        return view('kurikulum.generate-pdf.dokumen', compact(
            'kurikulum',
            'mkBySemester',
            'cpmkByMk',
            'pivotPlCpl',
            'pivotCplBk',
            'pivotMkCpl'
        ));
    }
}
