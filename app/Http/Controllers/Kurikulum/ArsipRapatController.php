<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\ArsipRapat;
use App\Models\Kurikulum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArsipRapatController extends Controller
{
    public function index(Kurikulum $kurikulum)
    {
        $hasAny    = $kurikulum->arsipRapat()->exists();
        $arsipList = $kurikulum->arsipRapat()
            ->with(['pembuat'])
            ->orderBy('tanggal', 'desc')
            ->paginate(15);

        return view('kurikulum.arsip-rapat.index', compact('kurikulum', 'arsipList', 'hasAny'));
    }

    public function create(Kurikulum $kurikulum)
    {
        return view('kurikulum.arsip-rapat.create', compact('kurikulum'));
    }

    public function store(Request $request, Kurikulum $kurikulum)
    {
        $validated = $request->validate([
            'judul_kegiatan' => 'required|string|max:255',
            'tanggal'        => 'required|date',
            'tempat'         => 'nullable|string|max:255',
            'temuan'         => 'nullable|string',
            'tindak_lanjut'  => 'nullable|string',
            'bukti_rapat'    => 'nullable|array|max:10',
            'bukti_rapat.*'  => 'file|max:20480|extensions:pdf,docx,png,jpg,jpeg,heic,heif',
        ]);

        $arsip = ArsipRapat::create([
            'id_kurikulum'   => $kurikulum->id,
            'dibuat_oleh'    => Auth::id(),
            'judul_kegiatan' => $validated['judul_kegiatan'],
            'tanggal'        => $validated['tanggal'],
            'tempat'         => $validated['tempat'] ?? null,
            'temuan'         => $validated['temuan'] ?? null,
            'tindak_lanjut'  => $validated['tindak_lanjut'] ?? null,
        ]);

        $this->storeFiles($request, $arsip);

        return redirect()
            ->route('kurikulum.arsip-rapat.index', $kurikulum)
            ->with('success', 'Arsip rapat berhasil ditambahkan.');
    }

    public function show(Kurikulum $kurikulum, ArsipRapat $arsipRapat)
    {
        $arsipRapat->load(['pembuat']);
        return view('kurikulum.arsip-rapat.show', compact('kurikulum', 'arsipRapat'));
    }

    public function edit(Kurikulum $kurikulum, ArsipRapat $arsipRapat)
    {
        return view('kurikulum.arsip-rapat.edit', compact('kurikulum', 'arsipRapat'));
    }

    public function update(Request $request, Kurikulum $kurikulum, ArsipRapat $arsipRapat)
    {
        $validated = $request->validate([
            'judul_kegiatan' => 'required|string|max:255',
            'tanggal'        => 'required|date',
            'tempat'         => 'nullable|string|max:255',
            'temuan'         => 'nullable|string',
            'tindak_lanjut'  => 'nullable|string',
            'bukti_rapat'    => 'nullable|array|max:10',
            'bukti_rapat.*'  => 'file|max:20480|extensions:pdf,docx,png,jpg,jpeg,heic,heif',
            'delete_files'   => 'nullable|array',
            'delete_files.*' => 'integer',
        ]);

        // Hapus file yang ditandai
        $existing   = $arsipRapat->file_lampiran ?? [];
        $deleteIdxs = array_map('intval', $request->input('delete_files', []));
        $kept = [];
        foreach ($existing as $i => $file) {
            if (in_array($i, $deleteIdxs, true)) {
                Storage::disk('public')->delete($file['path']);
            } else {
                $kept[] = $file;
            }
        }

        // Upload file baru
        if ($request->hasFile('bukti_rapat')) {
            foreach ($request->file('bukti_rapat') as $file) {
                $path   = $file->store("arsip-rapat/{$arsipRapat->id}", 'public');
                $kept[] = ['path' => $path, 'name' => $file->getClientOriginalName()];
            }
        }

        $arsipRapat->update([
            'judul_kegiatan' => $validated['judul_kegiatan'],
            'tanggal'        => $validated['tanggal'],
            'tempat'         => $validated['tempat'] ?? null,
            'temuan'         => $validated['temuan'] ?? null,
            'tindak_lanjut'  => $validated['tindak_lanjut'] ?? null,
            'file_lampiran'  => $kept ?: null,
        ]);

        return redirect()
            ->route('kurikulum.arsip-rapat.show', [$kurikulum, $arsipRapat])
            ->with('success', 'Arsip rapat berhasil diperbarui.');
    }

    public function destroy(Kurikulum $kurikulum, ArsipRapat $arsipRapat)
    {
        if ($arsipRapat->file_lampiran) {
            foreach ($arsipRapat->file_lampiran as $file) {
                Storage::disk('public')->delete($file['path']);
            }
            Storage::disk('public')->deleteDirectory("arsip-rapat/{$arsipRapat->id}");
        }

        $arsipRapat->delete();

        return redirect()
            ->route('kurikulum.arsip-rapat.index', $kurikulum)
            ->with('success', 'Arsip rapat berhasil dihapus.');
    }

    private function storeFiles(Request $request, ArsipRapat $arsip): void
    {
        if (! $request->hasFile('bukti_rapat')) return;

        $files = [];
        foreach ($request->file('bukti_rapat') as $file) {
            $path    = $file->store("arsip-rapat/{$arsip->id}", 'public');
            $files[] = ['path' => $path, 'name' => $file->getClientOriginalName()];
        }

        if ($files) {
            $arsip->update(['file_lampiran' => $files]);
        }
    }
}
