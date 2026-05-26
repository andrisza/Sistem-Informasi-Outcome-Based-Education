<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\BatasKetercapaian;
use App\Models\CplProdi;
use App\Models\HasilCpl;
use App\Models\HasilCpmk;
use App\Models\HasilPl;

class CplProgressController extends Controller
{
    public function index()
    {
        $mahasiswa = auth()->user();

        // Ambil semua hasil_cpl mahasiswa dengan relasi
        $hasilCplList = HasilCpl::with(['cpl', 'semester'])
            ->where('id_mahasiswa', $mahasiswa->id)
            ->orderBy('id_cpl')
            ->orderBy('id_semester')
            ->get();

        // Group by id_cpl, ambil nilai terbaru (atau rata-rata)
        $cplProgress = $hasilCplList->groupBy('id_cpl')->map(function ($items) {
            $latest      = $items->sortByDesc('id_semester')->first();
            $nilaiRata   = $items->avg('nilai_cpl');
            $tercapai    = $items->where('status_tercapai', true)->count() > 0;

            // Ambil batas ketercapaian
            $batas = BatasKetercapaian::where('id_cpl', $latest->id_cpl)->first();

            return [
                'cpl'            => $latest->cpl,
                'nilai_rata'     => round((float) $nilaiRata, 2),
                'nilai_latest'   => round((float) $latest->nilai_cpl, 2),
                'status_tercapai'=> $tercapai,
                'batas_nilai'    => $batas?->batas_nilai ?? 0,
                'jumlah_semester'=> $items->count(),
                'id_cpl'         => $latest->id_cpl,
            ];
        })->values();

        $totalCpl    = $cplProgress->count();
        $cplTercapai = $cplProgress->where('status_tercapai', true)->count();

        return view('mahasiswa.cpl-progress.index', compact(
            'mahasiswa',
            'cplProgress',
            'totalCpl',
            'cplTercapai',
        ));
    }

    public function show(int $cpl)
    {
        $mahasiswa = auth()->user();
        $cplProdi  = CplProdi::with('kurikulum')->findOrFail($cpl);

        // Ambil semua hasil CPL untuk CPL ini per semester
        $hasilPerSemester = HasilCpl::with('semester')
            ->where('id_mahasiswa', $mahasiswa->id)
            ->where('id_cpl', $cpl)
            ->orderBy('id_semester')
            ->get();

        // Batas ketercapaian
        $batas = BatasKetercapaian::where('id_cpl', $cpl)->first();

        // Ambil CPMK yang memetakan ke CPL ini
        $hasilCpmkList = HasilCpmk::with(['cpmk.mataKuliah', 'semester'])
            ->where('id_mahasiswa', $mahasiswa->id)
            ->whereHas('cpmk', fn($q) => $q->where('id_cpl', $cpl))
            ->orderBy('id_semester')
            ->get();

        // Nilai terbaru / rata-rata CPL ini
        $nilaiRata   = $hasilPerSemester->avg('nilai_cpl');
        $tercapai    = $hasilPerSemester->where('status_tercapai', true)->count() > 0;

        return view('mahasiswa.cpl-progress.show', compact(
            'mahasiswa',
            'cplProdi',
            'hasilPerSemester',
            'batas',
            'hasilCpmkList',
            'nilaiRata',
            'tercapai',
        ));
    }

    public function plProgress()
    {
        $mahasiswa = auth()->user();

        // Ambil semua hasil_pl mahasiswa
        $hasilPlList = HasilPl::with(['pl', 'semester'])
            ->where('id_mahasiswa', $mahasiswa->id)
            ->orderBy('id_pl')
            ->orderBy('id_semester')
            ->get();

        // Group by id_pl
        $plProgress = $hasilPlList->groupBy('id_pl')->map(function ($items) {
            $latest    = $items->sortByDesc('id_semester')->first();
            $nilaiRata = $items->avg('nilai_pl');
            $tercapai  = $items->where('status_tercapai', true)->count() > 0;

            return [
                'pl'             => $latest->pl,
                'nilai_rata'     => round((float) $nilaiRata, 2),
                'nilai_latest'   => round((float) $latest->nilai_pl, 2),
                'status_tercapai'=> $tercapai,
                'jumlah_semester'=> $items->count(),
                'id_pl'          => $latest->id_pl,
            ];
        })->values();

        $totalPl    = $plProgress->count();
        $plTercapai = $plProgress->where('status_tercapai', true)->count();

        return view('mahasiswa.pl-progress', compact(
            'mahasiswa',
            'plProgress',
            'totalPl',
            'plTercapai',
        ));
    }
}
