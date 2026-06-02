<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use App\Models\PengampuanMk;
use App\Models\SemesterAkademik;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Manajemen Pengampuan MK oleh Kaprodi.
 *
 * Pengampuan adalah penugasan resmi Dosen untuk mengampu (mengajar)
 * satu Mata Kuliah pada semester tertentu. Tanpa data ini, Dosen tidak
 * bisa membuat RPS untuk MK tersebut.
 */
class PengampuanMkController extends Controller
{
    /**
     * Daftar semua pengampuan, difilter per semester / MK / dosen.
     */
    public function index(Request $request)
    {
        $semesterAktif = SemesterAkademik::where('is_aktif', 1)->first();

        // Default filter ke semester aktif jika tidak ada filter manual
        $selectedSemester = $request->input('id_semester', $semesterAktif?->id);

        $query = PengampuanMk::with(['mataKuliah', 'dosen', 'semester'])
            ->orderBy('id_semester', 'desc');

        if ($selectedSemester) {
            $query->where('id_semester', $selectedSemester);
        }

        if ($request->filled('id_mk')) {
            $query->where('id_mk', $request->id_mk);
        }

        if ($request->filled('id_dosen')) {
            $query->where('id_dosen', $request->id_dosen);
        }

        $pengampuanList = $query->paginate(25)->withQueryString();

        $semesters  = SemesterAkademik::orderByDesc('id')->get();
        $dosenList  = User::where('role', 'dosen')->where('status_aktif', 'aktif')->orderBy('name')->get();
        $mkList     = MataKuliah::orderBy('semester')->orderBy('kode_mk')->get();

        // Hitung summary per semester yang dipilih
        $summary = null;
        if ($selectedSemester) {
            $summary = [
                'total_mk'         => $query->toBase()->distinct()->count('id_mk'),
                'total_dosen'      => $query->toBase()->distinct()->count('id_dosen'),
                'total_pengampuan' => PengampuanMk::where('id_semester', $selectedSemester)->count(),
            ];
        }

        return view('kaprodi.pengampuan.index', compact(
            'pengampuanList', 'semesters', 'dosenList', 'mkList',
            'selectedSemester', 'semesterAktif', 'summary'
        ));
    }

    /**
     * Form tambah pengampuan baru (satu dosen ke satu MK satu semester).
     */
    public function create()
    {
        $semesters = SemesterAkademik::orderByDesc('id')->get();
        $dosenList = User::where('role', 'dosen')->where('status_aktif', 'aktif')->orderBy('name')->get();
        $mkList    = MataKuliah::orderBy('semester')->orderBy('kode_mk')->get();

        return view('kaprodi.pengampuan.create', compact('semesters', 'dosenList', 'mkList'));
    }

    /**
     * Simpan satu data pengampuan.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_semester'    => 'required|integer|exists:semester_akademik,id',
            'id_mk'          => 'required|integer|exists:mata_kuliah,id',
            'id_dosen'       => 'required|integer|exists:users,id',
            'is_koordinator' => 'boolean',
        ]);

        // Cegah duplikat (unique key uk_pengampuan)
        $exists = PengampuanMk::where('id_mk', $validated['id_mk'])
            ->where('id_dosen', $validated['id_dosen'])
            ->where('id_semester', $validated['id_semester'])
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->with('error', 'Dosen ini sudah ditugaskan ke MK tersebut di semester yang dipilih.');
        }

        // Jika is_koordinator = true, pastikan tidak ada koordinator lain untuk MK ini di semester ini
        if (!empty($validated['is_koordinator'])) {
            PengampuanMk::where('id_mk', $validated['id_mk'])
                ->where('id_semester', $validated['id_semester'])
                ->update(['is_koordinator' => 0]);
        }

        PengampuanMk::create([
            'id_mk'          => $validated['id_mk'],
            'id_dosen'       => $validated['id_dosen'],
            'id_semester'    => $validated['id_semester'],
            'is_koordinator' => !empty($validated['is_koordinator']) ? 1 : 0,
        ]);

        $dosen = User::find($validated['id_dosen']);
        $mk    = MataKuliah::find($validated['id_mk']);
        $sem   = SemesterAkademik::find($validated['id_semester']);

        return redirect()
            ->route('kaprodi.pengampuan.index', ['id_semester' => $validated['id_semester']])
            ->with('success', "Dosen {$dosen->name} berhasil ditugaskan ke {$mk->kode_mk} ({$sem->nama}).");
    }

    /**
     * Batch assign: satu dosen ke banyak MK sekaligus dalam satu semester.
     */
    public function storeBatch(Request $request)
    {
        $request->validate([
            'id_semester'    => 'required|integer|exists:semester_akademik,id',
            'id_dosen'       => 'required|integer|exists:users,id',
            'mk_ids'         => 'required|array|min:1',
            'mk_ids.*'       => 'integer|exists:mata_kuliah,id',
            'id_koordinator' => 'nullable|integer|exists:mata_kuliah,id',
        ]);

        $semesterId = $request->id_semester;
        $dosenId    = $request->id_dosen;
        $mkIds      = $request->mk_ids;
        $koordinatorMkId = $request->id_koordinator;

        $inserted = 0;
        $skipped  = 0;

        foreach ($mkIds as $mkId) {
            $exists = PengampuanMk::where('id_mk', $mkId)
                ->where('id_dosen', $dosenId)
                ->where('id_semester', $semesterId)
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            $isKoordinator = ($koordinatorMkId == $mkId);

            // Jika koordinator, reset koordinator lama untuk MK ini
            if ($isKoordinator) {
                PengampuanMk::where('id_mk', $mkId)
                    ->where('id_semester', $semesterId)
                    ->update(['is_koordinator' => 0]);
            }

            PengampuanMk::create([
                'id_mk'          => $mkId,
                'id_dosen'       => $dosenId,
                'id_semester'    => $semesterId,
                'is_koordinator' => $isKoordinator ? 1 : 0,
            ]);
            $inserted++;
        }

        $pesan = "Berhasil menugaskan {$inserted} MK.";
        if ($skipped > 0) {
            $pesan .= " {$skipped} MK dilewati (sudah ada).";
        }

        return redirect()
            ->route('kaprodi.pengampuan.index', ['id_semester' => $semesterId])
            ->with('success', $pesan);
    }

    /**
     * Toggle status koordinator untuk satu pengampuan.
     */
    public function toggleKoordinator(PengampuanMk $pengampuan)
    {
        // Jika mau dijadikan koordinator, reset koordinator lama
        if (! $pengampuan->is_koordinator) {
            PengampuanMk::where('id_mk', $pengampuan->id_mk)
                ->where('id_semester', $pengampuan->id_semester)
                ->update(['is_koordinator' => 0]);
        }

        $pengampuan->update(['is_koordinator' => ! $pengampuan->is_koordinator]);

        $status = $pengampuan->is_koordinator ? 'Koordinator' : 'Pengampu Biasa';

        return back()->with('success', "Status dosen diubah menjadi {$status}.");
    }

    /**
     * Hapus satu pengampuan.
     */
    public function destroy(PengampuanMk $pengampuan)
    {
        $pengampuan->load(['dosen', 'mataKuliah', 'semester']);

        $info = "{$pengampuan->dosen->name} dari {$pengampuan->mataKuliah->kode_mk} ({$pengampuan->semester->nama})";

        $pengampuan->delete();

        return back()->with('success', "Pengampuan {$info} berhasil dihapus.");
    }
}
