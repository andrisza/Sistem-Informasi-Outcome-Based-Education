<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\Kurikulum;
use App\Models\MataKuliah;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalKurikulum = Kurikulum::count();
        $kurikulumAktif = Kurikulum::where('status', 'aktif')->count();
        $totalMk        = MataKuliah::count();
        $totalDosen     = User::where('role', 'dosen')->count();

        $kurikulumList = Kurikulum::where('status', 'aktif')
            ->withCount(['mataKuliah', 'cplProdi', 'pl'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('kurikulum.dashboard', compact(
            'totalKurikulum', 'kurikulumAktif', 'totalMk', 'totalDosen', 'kurikulumList',
        ));
    }
}
