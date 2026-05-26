<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\Kurikulum;

class DistribusiSemesterController extends Controller
{
    public function index(Kurikulum $kurikulum)
    {
        // Rekap dari mata_kuliah langsung (source of truth)
        $distribusi = $kurikulum->mataKuliah()
            ->selectRaw('semester, COUNT(*) as jumlah_mk, SUM(sks_total) as total_sks')
            ->groupBy('semester')
            ->orderBy('semester')
            ->get();

        $totalMk  = $distribusi->sum('jumlah_mk');
        $totalSks = $distribusi->sum('total_sks');

        // Distribusi dari tabel distribusi_semester jika ada
        $distribusiTable = $kurikulum->distribusiSemester()
            ->orderBy('semester')
            ->get()
            ->keyBy('semester');

        return view('kurikulum.distribusi-semester.index', compact(
            'kurikulum',
            'distribusi',
            'distribusiTable',
            'totalMk',
            'totalSks'
        ));
    }
}
