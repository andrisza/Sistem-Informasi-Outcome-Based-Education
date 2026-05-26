<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\PengampuanMk;
use App\Models\RepositoriMateri;
use App\Models\SemesterAkademik;
use Illuminate\Http\Request;

class MateriController extends Controller
{
    /**
     * List materi milik dosen yang login.
     */
    public function index(Request $request)
    {
        $dosenId = auth()->id();

        $query = RepositoriMateri::with(['mataKuliah', 'semester'])
            ->where('id_dosen', $dosenId);

        if ($request->filled('mk')) {
            $query->where('id_mk', $request->mk);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis_file', $request->jenis);
        }

        $materiList = $query->orderByDesc('created_at')->paginate(20);

        // MK yang diampu untuk filter
        $mkDiampu = PengampuanMk::with('mataKuliah')
            ->where('id_dosen', $dosenId)
            ->get()
            ->pluck('mataKuliah')
            ->unique('id');

        return view('dosen.materi.index', compact('materiList', 'mkDiampu'));
    }

    /**
     * Form upload materi.
     */
    public function create()
    {
        $dosenId = auth()->id();

        $pengampuan = PengampuanMk::with(['mataKuliah', 'semester'])
            ->where('id_dosen', $dosenId)
            ->get();

        $semesters = SemesterAkademik::orderByDesc('tahun_akademik')->get();

        $jenisOptions = ['modul', 'presentasi', 'referensi', 'tugas', 'video', 'lainnya'];

        return view('dosen.materi.create', compact('pengampuan', 'semesters', 'jenisOptions'));
    }

    /**
     * Simpan materi baru.
     */
    public function store(Request $request)
    {
        $dosenId = auth()->id();

        $validated = $request->validate([
            'id_mk'      => 'required|integer|exists:mata_kuliah,id',
            'id_semester'=> 'required|integer|exists:semester_akademik,id',
            'jenis_file' => 'required|in:modul,presentasi,referensi,tugas,video,lainnya',
            'nama_file'  => 'required|string|max:255',
            'deskripsi'  => 'nullable|string',
            'file_path'  => 'required|string|max:500',
        ]);

        // Pastikan dosen mengampu MK tersebut
        $pengampuan = PengampuanMk::where('id_dosen', $dosenId)
            ->where('id_mk', $validated['id_mk'])
            ->where('id_semester', $validated['id_semester'])
            ->first();

        if (! $pengampuan) {
            return back()->withErrors(['id_mk' => 'Anda tidak mengampu MK ini di semester tersebut.'])->withInput();
        }

        RepositoriMateri::create(array_merge($validated, [
            'id_dosen'   => $dosenId,
            'ukuran_kb'  => 0,
            'mime_type'  => 'text/plain',
            'created_at' => now(),
        ]));

        return redirect()->route('dosen.materi.index')
            ->with('success', 'Materi berhasil disimpan.');
    }

    /**
     * Soft delete materi (hanya milik dosen sendiri).
     */
    public function destroy(RepositoriMateri $materi)
    {
        if ($materi->id_dosen !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke materi ini.');
        }

        $materi->delete();

        return redirect()->route('dosen.materi.index')
            ->with('success', 'Materi berhasil dihapus.');
    }
}
