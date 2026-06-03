<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\EnrollmentMk;
use App\Models\MataKuliah;
use App\Models\SemesterAkademik;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnrollmentMkController extends Controller
{
    // ── Index ──────────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $semesters    = SemesterAkademik::orderByDesc('tahun_akademik')->get();
        $semesterAktif = SemesterAkademik::where('is_aktif', true)->first();

        $selectedSemester = $request->filled('semester')
            ? SemesterAkademik::find($request->semester)
            : $semesterAktif;

        $query = EnrollmentMk::with(['mahasiswa', 'mataKuliah', 'semester'])
            ->when($selectedSemester, fn($q) => $q->where('id_semester', $selectedSemester->id))
            ->when($request->filled('mk'), fn($q) => $q->where('id_mk', $request->mk))
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('search'), function($q) use ($request) {
                $q->whereHas('mahasiswa', fn($mq) =>
                    $mq->where('name', 'like', '%'.$request->search.'%')
                       ->orWhere('identifier', 'like', '%'.$request->search.'%')
                );
            });

        $enrollments = $query->orderBy('id_mk')->orderBy('id_mahasiswa')->paginate(30)->withQueryString();

        $mkList = MataKuliah::orderBy('semester')->orderBy('kode_mk')->get();

        // Statistik ringkas
        $stats = [
            'total'     => EnrollmentMk::when($selectedSemester, fn($q) => $q->where('id_semester', $selectedSemester->id))->count(),
            'aktif'     => EnrollmentMk::when($selectedSemester, fn($q) => $q->where('id_semester', $selectedSemester->id))->where('status','aktif')->count(),
            'lulus'     => EnrollmentMk::when($selectedSemester, fn($q) => $q->where('id_semester', $selectedSemester->id))->where('status','lulus')->count(),
            'mahasiswa' => EnrollmentMk::when($selectedSemester, fn($q) => $q->where('id_semester', $selectedSemester->id))->distinct('id_mahasiswa')->count('id_mahasiswa'),
        ];

        return view('kaprodi.enrollment.index', compact(
            'enrollments', 'semesters', 'selectedSemester', 'mkList', 'stats'
        ));
    }

    // ── Create / Store (tambah satu) ───────────────────────────────────────────

    public function create()
    {
        $mahasiswaList = User::where('role', 'mahasiswa')->where('status_aktif', true)
            ->orderBy('name')->get();
        $mkList        = MataKuliah::orderBy('semester')->orderBy('kode_mk')->get();
        $semesters     = SemesterAkademik::orderByDesc('tahun_akademik')->get();
        $semesterAktif = SemesterAkademik::where('is_aktif', true)->first();

        return view('kaprodi.enrollment.create', compact(
            'mahasiswaList', 'mkList', 'semesters', 'semesterAktif'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_mahasiswa'   => 'required|integer|exists:users,id',
            'id_mk'          => 'required|integer|exists:mata_kuliah,id',
            'id_semester'    => 'required|integer|exists:semester_akademik,id',
            'tanggal_daftar' => 'nullable|date',
            'status'         => 'required|in:aktif,mengulang,lulus,tidak_lulus',
        ]);

        // Cek duplikat
        $exists = EnrollmentMk::where('id_mahasiswa', $validated['id_mahasiswa'])
            ->where('id_mk', $validated['id_mk'])
            ->where('id_semester', $validated['id_semester'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['id_mahasiswa' => 'Mahasiswa ini sudah terdaftar pada MK dan semester tersebut.'])->withInput();
        }

        EnrollmentMk::create($validated + ['tanggal_daftar' => $validated['tanggal_daftar'] ?? now()->toDateString()]);

        return redirect()->route('kaprodi.enrollment.index')
            ->with('success', 'Mahasiswa berhasil didaftarkan ke mata kuliah.');
    }

    // ── Batch store (tambah banyak mahasiswa ke satu MK) ──────────────────────

    public function storeBatch(Request $request)
    {
        $request->validate([
            'id_mk'            => 'required|integer|exists:mata_kuliah,id',
            'id_semester'      => 'required|integer|exists:semester_akademik,id',
            'id_mahasiswa'     => 'required|array|min:1',
            'id_mahasiswa.*'   => 'integer|exists:users,id',
            'status'           => 'required|in:aktif,mengulang,lulus,tidak_lulus',
        ]);

        $tanggal = now()->toDateString();
        $added   = 0;
        $skipped = 0;

        DB::transaction(function () use ($request, $tanggal, &$added, &$skipped) {
            foreach ($request->id_mahasiswa as $mhsId) {
                $exists = EnrollmentMk::where('id_mahasiswa', $mhsId)
                    ->where('id_mk', $request->id_mk)
                    ->where('id_semester', $request->id_semester)
                    ->exists();

                if ($exists) { $skipped++; continue; }

                EnrollmentMk::create([
                    'id_mahasiswa'   => $mhsId,
                    'id_mk'          => $request->id_mk,
                    'id_semester'    => $request->id_semester,
                    'tanggal_daftar' => $tanggal,
                    'status'         => $request->status,
                ]);
                $added++;
            }
        });

        $msg = "{$added} mahasiswa berhasil didaftarkan.";
        if ($skipped) $msg .= " {$skipped} dilewati (sudah terdaftar).";

        return redirect()->route('kaprodi.enrollment.index')
            ->with('success', $msg);
    }

    // ── Edit / Update status ───────────────────────────────────────────────────

    public function edit(EnrollmentMk $enrollment)
    {
        $enrollment->load(['mahasiswa', 'mataKuliah', 'semester']);
        return view('kaprodi.enrollment.edit', compact('enrollment'));
    }

    public function update(Request $request, EnrollmentMk $enrollment)
    {
        $validated = $request->validate([
            'status'         => 'required|in:aktif,mengulang,lulus,tidak_lulus',
            'tanggal_daftar' => 'nullable|date',
        ]);

        $enrollment->update($validated);

        return redirect()->route('kaprodi.enrollment.index', ['semester' => $enrollment->id_semester])
            ->with('success', 'Status enrollment berhasil diperbarui.');
    }

    // ── Destroy ────────────────────────────────────────────────────────────────

    public function destroy(EnrollmentMk $enrollment)
    {
        $semId = $enrollment->id_semester;
        $enrollment->delete();

        return redirect()->route('kaprodi.enrollment.index', ['semester' => $semId])
            ->with('success', 'Data enrollment berhasil dihapus.');
    }

    // ── Batch create form (pilih banyak mahasiswa) ────────────────────────────

    public function batchCreate()
    {
        $mahasiswaList = User::where('role', 'mahasiswa')->where('status_aktif', true)
            ->orderBy('name')->get();
        $mkList    = MataKuliah::orderBy('semester')->orderBy('kode_mk')->get();
        $semesters = SemesterAkademik::orderByDesc('tahun_akademik')->get();
        $semesterAktif = SemesterAkademik::where('is_aktif', true)->first();

        return view('kaprodi.enrollment.batch', compact(
            'mahasiswaList', 'mkList', 'semesters', 'semesterAktif'
        ));
    }
}
