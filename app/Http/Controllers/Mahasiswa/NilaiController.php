<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\EnrollmentMk;
use App\Models\HasilCpmk;
use App\Models\KomponenAsesmen;
use App\Models\MataKuliah;
use App\Models\NilaiMahasiswa;
use App\Models\SemesterAkademik;

class NilaiController extends Controller
{
    public function index()
    {
        $mahasiswa = auth()->user();

        // Ambil semua enrollment mahasiswa dengan relasi
        $enrollments = EnrollmentMk::with(['mataKuliah', 'semester'])
            ->where('id_mahasiswa', $mahasiswa->id)
            ->orderByDesc('id')
            ->get();

        // Untuk setiap enrollment, hitung nilai akhir tertimbang
        $enrollments = $enrollments->map(function ($enrollment) use ($mahasiswa) {
            $mk       = $enrollment->id_mk;
            $semester = $enrollment->id_semester;

            // Ambil komponen asesmen MK + semester ini
            $komponens = KomponenAsesmen::where('id_mk', $mk)
                ->where('id_semester', $semester)
                ->get();

            // Ambil nilai mahasiswa untuk komponen-komponen tsb
            $nilaiList = NilaiMahasiswa::where('id_mahasiswa', $mahasiswa->id)
                ->where('id_semester', $semester)
                ->whereIn('id_komponen', $komponens->pluck('id'))
                ->get()
                ->keyBy('id_komponen');

            // Hitung nilai akhir tertimbang: sum(nilai_mentah/skor_maks * bobot_persen)
            $nilaiAkhir   = 0;
            $totalBobot   = 0;
            foreach ($komponens as $komponen) {
                $nilai = $nilaiList->get($komponen->id);
                if ($nilai && $komponen->skor_maks > 0) {
                    $nilaiAkhir += ($nilai->nilai_mentah / $komponen->skor_maks) * $komponen->bobot_persen;
                }
                $totalBobot += $komponen->bobot_persen;
            }

            $enrollment->nilai_akhir = round($nilaiAkhir, 2);
            $enrollment->total_bobot = $totalBobot;
            return $enrollment;
        });

        // Group by semester
        $grouped = $enrollments->groupBy(fn($e) => $e->semester?->nama ?? 'Tidak diketahui');

        return view('mahasiswa.nilai.index', compact('grouped', 'mahasiswa'));
    }

    public function show(int $mk, int $semester)
    {
        $mahasiswa        = auth()->user();
        $mataKuliah       = MataKuliah::with('kurikulum')->findOrFail($mk);
        $semesterAkademik = SemesterAkademik::findOrFail($semester);

        // Pastikan mahasiswa terdaftar di MK ini pada semester ini
        $enrollment = EnrollmentMk::where('id_mahasiswa', $mahasiswa->id)
            ->where('id_mk', $mk)
            ->where('id_semester', $semester)
            ->firstOrFail();

        // Ambil semua komponen asesmen MK + semester
        $komponens = KomponenAsesmen::where('id_mk', $mk)
            ->where('id_semester', $semester)
            ->orderBy('id')
            ->get();

        // Ambil nilai mahasiswa, key by id_komponen
        $nilaiList = NilaiMahasiswa::where('id_mahasiswa', $mahasiswa->id)
            ->where('id_semester', $semester)
            ->whereIn('id_komponen', $komponens->pluck('id'))
            ->get()
            ->keyBy('id_komponen');

        // Gabungkan komponen dengan nilai
        $komponenDenganNilai = $komponens->map(function ($komponen) use ($nilaiList) {
            $nilai         = $nilaiList->get($komponen->id);
            $nilaiMentah   = $nilai?->nilai_mentah ?? null;
            $nilaiTerboboti = null;

            if ($nilaiMentah !== null && $komponen->skor_maks > 0) {
                $nilaiTerboboti = round(($nilaiMentah / $komponen->skor_maks) * $komponen->bobot_persen, 2);
            }

            return [
                'komponen'        => $komponen,
                'nilai_mentah'    => $nilaiMentah,
                'nilai_terboboti' => $nilaiTerboboti,
            ];
        });

        // Hitung total nilai akhir
        $nilaiAkhir = $komponenDenganNilai->sum(fn($item) => $item['nilai_terboboti'] ?? 0);

        // Ambil hasil CPMK
        $hasilCpmkList = HasilCpmk::with('cpmk')
            ->where('id_mahasiswa', $mahasiswa->id)
            ->where('id_semester', $semester)
            ->whereHas('cpmk', fn($q) => $q->where('id_mk', $mk))
            ->orderBy('id')
            ->get();

        return view('mahasiswa.nilai.show', compact(
            'mahasiswa',
            'mataKuliah',
            'semesterAkademik',
            'enrollment',
            'komponenDenganNilai',
            'nilaiAkhir',
            'hasilCpmkList',
        ));
    }
}
