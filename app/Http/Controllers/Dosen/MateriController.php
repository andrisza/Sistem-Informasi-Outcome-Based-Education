<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\PengampuanMk;
use App\Models\RepositoriMateri;
use App\Models\SemesterAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    /** Tipe MIME yang diizinkan per kategori jenis_file. */
    private const ALLOWED_MIMES = [
        'pdf,doc,docx,ppt,pptx,xls,xlsx,txt,zip,rar,7z,mp4,avi,mov,mkv,jpg,jpeg,png,gif,svg',
    ];

    /** Ukuran maksimum file (50 MB dalam kilobyte). */
    private const MAX_SIZE_KB = 51200;

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

        $semesters    = SemesterAkademik::orderByDesc('tahun_akademik')->get();
        $jenisOptions = ['modul', 'presentasi', 'referensi', 'tugas', 'video', 'lainnya'];

        return view('dosen.materi.create', compact('pengampuan', 'semesters', 'jenisOptions'));
    }

    /**
     * Simpan materi baru.
     *
     * View harus menggunakan enctype="multipart/form-data" dan input <input type="file" name="file">.
     */
    public function store(Request $request)
    {
        $dosenId = auth()->id();

        $validated = $request->validate([
            'id_mk'       => 'required|integer|exists:mata_kuliah,id',
            'id_semester' => 'required|integer|exists:semester_akademik,id',
            'jenis_file'  => 'required|in:modul,presentasi,referensi,tugas,video,lainnya',
            'nama_file'   => 'required|string|max:255',
            'deskripsi'   => 'nullable|string',
            // Validasi file upload nyata: tipe MIME & ukuran maksimal 50 MB
            'file'        => [
                'required',
                'file',
                'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt,zip,rar,7z,mp4,avi,mov,mkv,jpg,jpeg,png,gif,svg',
                'max:' . self::MAX_SIZE_KB,
            ],
        ]);

        // Pastikan dosen mengampu MK tersebut
        $pengampuan = PengampuanMk::where('id_dosen', $dosenId)
            ->where('id_mk', $validated['id_mk'])
            ->where('id_semester', $validated['id_semester'])
            ->first();

        if (!$pengampuan) {
            return back()->withErrors(['id_mk' => 'Anda tidak mengampu MK ini di semester tersebut.'])->withInput();
        }

        // Simpan file ke storage/app/public/materi/{id_mk}/
        $uploadedFile = $request->file('file');
        $path         = $uploadedFile->store("materi/{$validated['id_mk']}", 'public');

        RepositoriMateri::create([
            'id_mk'       => $validated['id_mk'],
            'id_semester' => $validated['id_semester'],
            'id_dosen'    => $dosenId,
            'jenis_file'  => $validated['jenis_file'],
            'nama_file'   => $validated['nama_file'],
            'deskripsi'   => $validated['deskripsi'] ?? null,
            'file_path'   => $path,
            'ukuran_kb'   => (int) ceil($uploadedFile->getSize() / 1024),
            'mime_type'   => $uploadedFile->getMimeType(),
            'created_at'  => now(),
        ]);

        return redirect()->route('dosen.materi.index')
            ->with('success', 'Materi berhasil diunggah.');
    }

    /**
     * Soft delete materi (hanya milik dosen sendiri).
     */
    public function destroy(RepositoriMateri $materi)
    {
        if ($materi->id_dosen !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke materi ini.');
        }

        // Hapus file fisik dari storage jika ada
        if ($materi->file_path && Storage::disk('public')->exists($materi->file_path)) {
            Storage::disk('public')->delete($materi->file_path);
        }

        $materi->delete();

        return redirect()->route('dosen.materi.index')
            ->with('success', 'Materi berhasil dihapus.');
    }
}
