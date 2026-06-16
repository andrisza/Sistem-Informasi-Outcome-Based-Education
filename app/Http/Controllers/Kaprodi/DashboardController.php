<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Kurikulum;
use App\Models\RpsHeader;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers    = User::count();
        $kurikulumAktif = Kurikulum::where('status', 'aktif')->count();
        $rpsPending    = RpsHeader::where('status', 'review')->count();

        $rpsPendingList = RpsHeader::with(['mataKuliah', 'dosenPengembang', 'semester'])
                                   ->where('status', 'review')
                                   ->orderByDesc('updated_at')
                                   ->take(5)
                                   ->get();

        $recentActivity = ActivityLog::with('user')
                                     ->orderByDesc('created_at')
                                     ->take(10)
                                     ->get();

        return view('kaprodi.dashboard', compact(
            'totalUsers', 'kurikulumAktif', 'rpsPending',
            'rpsPendingList', 'recentActivity'
        ));
    }
}
