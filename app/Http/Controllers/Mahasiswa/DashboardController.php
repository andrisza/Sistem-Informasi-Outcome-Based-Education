<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\EnrollmentMk;
use App\Models\HasilCpl;
use App\Models\MataKuliah;
use App\Models\PengampuanMk;
use App\Models\RpsHeader;
use App\Models\SemesterAkademik;

class DashboardController extends Controller
{
    public function index()
    {
        $mahasiswa  = auth()->user();
        $semesterAktif = SemesterAkademik::where('is_aktif', true)->first();

        // MK yang diikuti semester aktif
        $enrollmentAktif = collect();
        if ($semesterAktif) {
            $enrollmentAktif = EnrollmentMk::with([
                'mataKuliah.kurikulum',
            ])
                ->where('id_mahasiswa', $mahasiswa->id)
                ->where('id_semester', $semesterAktif->id)
                ->get();
        }

        // Hitung SKS total semester ini
        $sksTotal = $enrollmentAktif->sum(fn($e) => $e->mataKuliah?->sks_total ?? 0);

        // Ambil dosen pengampu untuk setiap MK semester aktif
        $dosenMap = collect();
        if ($semesterAktif && $enrollmentAktif->isNotEmpty()) {
            $mkIds = $enrollmentAktif->pluck('id_mk')->unique();
            $dosenMap = PengampuanMk::with('dosen')
                ->where('id_semester', $semesterAktif->id)
                ->whereIn('id_mk', $mkIds)
                ->get()
                ->groupBy('id_mk');
        }

        // Progress CPL ringkasan
        $hasilCplList = HasilCpl::with('cpl')
            ->where('id_mahasiswa', $mahasiswa->id)
            ->get();

        $totalCpl    = $hasilCplList->pluck('id_cpl')->unique()->count();
        $cplTercapai = $hasilCplList->where('status_tercapai', true)->pluck('id_cpl')->unique()->count();

        // Ringkasan CPL per kode (ambil nilai rata-rata per CPL)
        $cplSummary = $hasilCplList
            ->groupBy('id_cpl')
            ->map(function ($items) {
                $first     = $items->first();
                $nilaiRata = $items->avg('nilai_cpl');
                $tercapai  = $items->where('status_tercapai', true)->count() > 0;
                return [
                    'kode_cpl'        => $first->cpl?->kode_cpl ?? '-',
                    'deskripsi'       => $first->cpl?->deskripsi ?? '-',
                    'nilai'           => round((float) $nilaiRata, 2),
                    'batas_nilai'     => 65.0, // default threshold; no kurikulum-specific lookup here
                    'status_tercapai' => $tercapai,
                ];
            })
            ->values()
            ->sortBy('kode_cpl')
            ->values(); // re-index after sort

        $cplRadar  = $cplSummary; // all items for chart
        $cplSummary = $cplSummary->take(5); // first 5 for progress bars

        // Total MK yang pernah diikuti (semua semester)
        $totalMkDiikuti = EnrollmentMk::where('id_mahasiswa', $mahasiswa->id)->distinct('id_mk')->count('id_mk');

        return view('mahasiswa.dashboard', compact(
            'mahasiswa',
            'semesterAktif',
            'enrollmentAktif',
            'sksTotal',
            'dosenMap',
            'totalCpl',
            'cplTercapai',
            'cplSummary',
            'cplRadar',
            'totalMkDiikuti',
        ));
    }

    public function rpsShow(int $mk, int $semester)
    {
        $mahasiswa       = auth()->user();
        $mataKuliah      = MataKuliah::findOrFail($mk);
        $semesterAkademik = SemesterAkademik::findOrFail($semester);

        // Pastikan mahasiswa terdaftar di MK ini pada semester ini
        $enrolled = EnrollmentMk::where('id_mahasiswa', $mahasiswa->id)
            ->where('id_mk', $mk)
            ->where('id_semester', $semester)
            ->exists();

        if (! $enrolled) {
            return back()->with('error', 'Anda tidak terdaftar pada mata kuliah ini.');
        }

        // Ambil RPS yang sudah disahkan
        $rps = RpsHeader::with(['pertemuan', 'dosenPengembang', 'semester'])
            ->where('id_mk', $mk)
            ->where('id_semester', $semester)
            ->where('status', 'disahkan')
            ->first();

        if (! $rps) {
            return back()->with('error', 'RPS untuk mata kuliah ini belum tersedia atau belum disahkan.');
        }

        return view('mahasiswa.rps.show', compact('rps', 'mataKuliah', 'semesterAkademik'));
    }
}
