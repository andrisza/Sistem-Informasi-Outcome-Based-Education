<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\SemesterAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SemesterAkademikController extends Controller
{
    public function index()
    {
        $semesters = SemesterAkademik::orderByDesc('tanggal_mulai')
                                     ->orderByDesc('id')
                                     ->paginate(20);

        return view('kaprodi.semester-akademik.index', compact('semesters'));
    }

    public function create()
    {
        return view('kaprodi.semester-akademik.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tahun_akademik'  => ['required', 'string', 'max:10'],
            'jenis'           => [
                'required',
                Rule::in(['Gasal', 'Genap']),
                Rule::unique('semester_akademik', 'jenis')
                    ->where('tahun_akademik', $request->input('tahun_akademik')),
            ],
            'tanggal_mulai'   => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
        ], [
            'jenis.unique' => 'Semester dengan tahun akademik dan jenis tersebut sudah ada.',
        ]);

        $data['is_aktif'] = 0;

        $semester = SemesterAkademik::create($data);

        ActivityLog::record('create', SemesterAkademik::class, $semester->id, [], $semester->only(['nama', 'tahun_akademik', 'jenis']));

        return redirect()->route('kaprodi.semester-akademik.index')
                         ->with('success', "Semester {$semester->nama} berhasil ditambahkan.");
    }

    public function edit(SemesterAkademik $semesterAkademik)
    {
        return view('kaprodi.semester-akademik.edit', compact('semesterAkademik'));
    }

    public function update(Request $request, SemesterAkademik $semesterAkademik)
    {
        $data = $request->validate([
            'tahun_akademik'  => ['required', 'string', 'max:10'],
            'jenis'           => [
                'required',
                Rule::in(['Gasal', 'Genap']),
                Rule::unique('semester_akademik', 'jenis')
                    ->where('tahun_akademik', $request->input('tahun_akademik'))
                    ->ignore($semesterAkademik->id),
            ],
            'tanggal_mulai'   => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
        ], [
            'jenis.unique' => 'Semester dengan tahun akademik dan jenis tersebut sudah ada.',
        ]);

        $old = $semesterAkademik->only(['nama', 'tahun_akademik', 'jenis']);

        $semesterAkademik->update($data);

        ActivityLog::record('update', SemesterAkademik::class, $semesterAkademik->id, $old, $semesterAkademik->fresh()->only(['nama', 'tahun_akademik', 'jenis']));

        return redirect()->route('kaprodi.semester-akademik.index')
                         ->with('success', 'Semester akademik berhasil diperbarui.');
    }

    public function destroy(SemesterAkademik $semesterAkademik)
    {
        ActivityLog::record('delete', SemesterAkademik::class, $semesterAkademik->id, $semesterAkademik->only(['nama']));

        $semesterAkademik->delete();

        return redirect()->route('kaprodi.semester-akademik.index')
                         ->with('success', "Semester {$semesterAkademik->nama} berhasil dihapus.");
    }

    public function aktifkan(SemesterAkademik $semesterAkademik)
    {
        DB::transaction(function () use ($semesterAkademik) {
            SemesterAkademik::where('is_aktif', 1)->update(['is_aktif' => 0]);
            $semesterAkademik->update(['is_aktif' => 1]);
        });

        ActivityLog::record('aktifkan', SemesterAkademik::class, $semesterAkademik->id);

        return back()->with('success', "Semester {$semesterAkademik->nama} sekarang aktif.");
    }
}
