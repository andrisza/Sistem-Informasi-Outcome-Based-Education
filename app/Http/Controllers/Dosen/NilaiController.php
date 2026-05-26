<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\EnrollmentMk;
use App\Models\KomponenAsesmen;
use App\Models\MataKuliah;
use App\Models\NilaiMahasiswa;
use App\Models\PengampuanMk;
use App\Models\SemesterAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NilaiController extends Controller
{
    /**
     * List MK yang diampu dosen ini.
     */
    public function index()
    {
        $dosenId = auth()->id();

        $pengampuan = PengampuanMk::with(['mataKuliah', 'semester'])
            ->where('id_dosen', $dosenId)
            ->orderByDesc('id_semester')
            ->paginate(20);

        return view('dosen.nilai.index', compact('pengampuan'));
    }

    /**
     * Form input nilai per MK dan semester.
     */
    public function form(string $mk, string $semester)
    {
        $dosenId       = auth()->id();
        $mataKuliah    = MataKuliah::findOrFail($mk);
        $semesterModel = SemesterAkademik::findOrFail($semester);

        // Pastikan dosen mengampu MK di semester ini
        $this->authorizePengampuan($dosenId, $mataKuliah->id, $semesterModel->id);

        // Komponen asesmen untuk MK + semester ini
        $komponen = KomponenAsesmen::where('id_mk', $mataKuliah->id)
            ->where('id_semester', $semesterModel->id)
            ->orderBy('id')
            ->get();

        // Mahasiswa yang terdaftar
        $enrollments = EnrollmentMk::with('mahasiswa')
            ->where('id_mk', $mataKuliah->id)
            ->where('id_semester', $semesterModel->id)
            ->where('status', 'aktif')
            ->orderBy('id')
            ->get();

        // Ambil nilai yang sudah ada: [id_mahasiswa][id_komponen] = nilai_mentah
        $nilaiExisting = NilaiMahasiswa::where('id_semester', $semesterModel->id)
            ->whereIn('id_komponen', $komponen->pluck('id'))
            ->get()
            ->groupBy('id_mahasiswa')
            ->map(fn($rows) => $rows->keyBy('id_komponen')->map->nilai_mentah);

        return view('dosen.nilai.form', compact(
            'mataKuliah',
            'semesterModel',
            'komponen',
            'enrollments',
            'nilaiExisting',
        ));
    }

    /**
     * Batch upsert nilai mahasiswa.
     */
    public function store(Request $request, string $mk, string $semester)
    {
        $dosenId       = auth()->id();
        $mataKuliah    = MataKuliah::findOrFail($mk);
        $semesterModel = SemesterAkademik::findOrFail($semester);

        $this->authorizePengampuan($dosenId, $mataKuliah->id, $semesterModel->id);

        $request->validate([
            'nilai'                  => 'required|array',
            'nilai.*.*'              => 'nullable|numeric|min:0|max:100',
        ]);

        // nilai[id_mahasiswa][id_komponen] = nilai_mentah
        DB::transaction(function () use ($request, $semesterModel) {
            foreach ($request->nilai as $idMahasiswa => $komponenNilai) {
                foreach ($komponenNilai as $idKomponen => $nilaiMentah) {
                    if ($nilaiMentah === null || $nilaiMentah === '') {
                        continue;
                    }

                    NilaiMahasiswa::updateOrCreate(
                        [
                            'id_mahasiswa' => (int) $idMahasiswa,
                            'id_komponen'  => (int) $idKomponen,
                            'id_semester'  => $semesterModel->id,
                        ],
                        ['nilai_mentah' => (float) $nilaiMentah]
                    );
                }
            }
        });

        return redirect()->route('dosen.nilai.rekap', [$mk, $semester])
            ->with('success', 'Nilai berhasil disimpan.');
    }

    /**
     * Rekap nilai per mahasiswa (weighted sum).
     */
    public function rekap(string $mk, string $semester)
    {
        $dosenId       = auth()->id();
        $mataKuliah    = MataKuliah::findOrFail($mk);
        $semesterModel = SemesterAkademik::findOrFail($semester);

        $this->authorizePengampuan($dosenId, $mataKuliah->id, $semesterModel->id);

        $komponen = KomponenAsesmen::where('id_mk', $mataKuliah->id)
            ->where('id_semester', $semesterModel->id)
            ->orderBy('id')
            ->get();

        $enrollments = EnrollmentMk::with('mahasiswa')
            ->where('id_mk', $mataKuliah->id)
            ->where('id_semester', $semesterModel->id)
            ->orderBy('id')
            ->get();

        // nilai[id_mahasiswa][id_komponen] = nilai_mentah
        $nilaiMap = NilaiMahasiswa::where('id_semester', $semesterModel->id)
            ->whereIn('id_komponen', $komponen->pluck('id'))
            ->get()
            ->groupBy('id_mahasiswa')
            ->map(fn($rows) => $rows->keyBy('id_komponen')->map->nilai_mentah);

        // Hitung total weighted untuk setiap mahasiswa
        $rekap = $enrollments->map(function ($enrollment) use ($komponen, $nilaiMap) {
            $mahasiswa  = $enrollment->mahasiswa;
            $nilaiPerMk = $nilaiMap->get($mahasiswa->id, collect());
            $totalBobot = 0;
            $totalNilai = 0;

            foreach ($komponen as $k) {
                $nilaiMentah = $nilaiPerMk->get($k->id);
                if ($nilaiMentah !== null) {
                    $bobot = $k->bobot_persen / 100;
                    $totalNilai += ($nilaiMentah / ($k->skor_maks ?: 100)) * 100 * $bobot;
                    $totalBobot += $bobot;
                }
            }

            $nilaiAkhir = $totalBobot > 0 ? round($totalNilai / $totalBobot, 2) : null;
            $lulus      = $nilaiAkhir !== null && $nilaiAkhir >= 55;

            return [
                'mahasiswa'   => $mahasiswa,
                'nilai'       => $nilaiPerMk,
                'nilai_akhir' => $nilaiAkhir,
                'lulus'       => $lulus,
            ];
        });

        return view('dosen.nilai.rekap', compact(
            'mataKuliah',
            'semesterModel',
            'komponen',
            'rekap',
        ));
    }

    // ── Helpers ────────────────────────────────────────────────

    private function authorizePengampuan(int $dosenId, int $mkId, int $semesterId): void
    {
        $exists = PengampuanMk::where('id_dosen', $dosenId)
            ->where('id_mk', $mkId)
            ->where('id_semester', $semesterId)
            ->exists();

        if (! $exists) {
            abort(403, 'Anda tidak mengampu mata kuliah ini di semester tersebut.');
        }
    }
}
