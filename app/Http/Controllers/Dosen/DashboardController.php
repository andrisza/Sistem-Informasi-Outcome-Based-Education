<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\JurnalMengajar;
use App\Models\PengampuanMk;
use App\Models\RpsHeader;
use App\Models\SemesterAkademik;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $dosenId = auth()->id();

        $semesterAktif = SemesterAkademik::where('is_aktif', true)->first();

        // MK yang diampu semester aktif
        $pengampuanAktif = PengampuanMk::with(['mataKuliah', 'semester'])
            ->where('id_dosen', $dosenId)
            ->when($semesterAktif, fn($q) => $q->where('id_semester', $semesterAktif->id))
            ->get();

        // Jumlah total MK yang diampu semester aktif
        $jumlahMkAktif = $pengampuanAktif->count();

        // Jumlah RPS yang dibuat dosen ini
        $jumlahRps = RpsHeader::where('id_dosen_pengembang', $dosenId)->count();

        // Jurnal bulan ini
        $jumlahJurnalBulanIni = JurnalMengajar::where('id_dosen', $dosenId)
            ->whereYear('tanggal_pelaksanaan', now()->year)
            ->whereMonth('tanggal_pelaksanaan', now()->month)
            ->count();

        // RPS pending (status review atau draft)
        $rpsDraft = RpsHeader::where('id_dosen_pengembang', $dosenId)
            ->where('status', 'draft')
            ->count();

        return view('dosen.dashboard', compact(
            'semesterAktif',
            'pengampuanAktif',
            'jumlahMkAktif',
            'jumlahRps',
            'jumlahJurnalBulanIni',
            'rpsDraft',
        ));
    }

    public function pengampuan(Request $request)
    {
        $dosenId = auth()->id();

        $semesters = SemesterAkademik::orderByDesc('tahun_akademik')->orderByDesc('id')->get();

        $query = PengampuanMk::with(['mataKuliah', 'semester'])
            ->where('id_dosen', $dosenId);

        if ($request->filled('semester')) {
            $query->where('id_semester', $request->semester);
        }

        $pengampuan = $query->orderByDesc('id')->paginate(20);

        return view('dosen.pengampuan', compact('pengampuan', 'semesters'));
    }
}
