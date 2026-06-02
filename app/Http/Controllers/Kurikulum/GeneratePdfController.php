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

        // Mata kuliah dikelompokkan per semester
        $mkBySemester = $kurikulum->mataKuliah->groupBy('semester');

        // CPMK per MK
        $cpmkByMk = \App\Models\Cpmk::where('id_kurikulum', $kurikulum->id)
            ->with('cplProdi')
            ->orderBy('urutan')
            ->get()
            ->groupBy('id_mk');

        // Pivot PL-CPL
        $pivotPlCpl = \Illuminate\Support\Facades\DB::table('pivot_pl_cpl')
            ->whereIn('id_pl', $kurikulum->pl->pluck('id'))
            ->get()
            ->groupBy('id_pl')
            ->map(fn ($rows) => $rows->pluck('id_cpl')->toArray());

        return view('kurikulum.generate-pdf.dokumen', compact(
            'kurikulum',
            'mkBySemester',
            'cpmkByMk',
            'pivotPlCpl'
        ));
    }
}
