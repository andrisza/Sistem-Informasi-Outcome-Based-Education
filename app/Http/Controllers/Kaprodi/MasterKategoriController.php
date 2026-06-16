<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\MasterKategori;
use Illuminate\Http\Request;

class MasterKategoriController extends Controller
{
    public function index()
    {
        $all = MasterKategori::orderBy('jenis')->orderBy('urutan')->orderBy('nama')->get();

        $grouped = $all->groupBy('jenis');

        $view = request()->routeIs('admin.*')
            ? 'admin.master-kategori.index'
            : 'kaprodi.master-kategori.index';

        return view($view, compact('grouped'));
    }

    public function store(Request $request)
    {
        $jenis = $request->input('jenis', '');
        $validated = $request->validate([
            'jenis'     => 'required|string|max:50|regex:/^[a-z0-9_]+$/',
            'nama'      => 'required|string|max:150|unique:master_kategori,nama,NULL,id,jenis,' . $jenis,
            'deskripsi' => 'nullable|string',
            'urutan'    => 'nullable|integer|min:0',
        ], [
            'jenis.regex' => 'Kode jenis hanya boleh berisi huruf kecil, angka, dan underscore.',
        ]);

        if (empty($validated['urutan'])) {
            $validated['urutan'] = MasterKategori::where('jenis', $validated['jenis'])->max('urutan') + 1;
        }

        $validated['is_aktif'] = true;

        MasterKategori::create($validated);

        $route = request()->routeIs('admin.*')
            ? 'admin.master-kategori.index'
            : 'kaprodi.master-kategori.index';

        return redirect()
            ->route($route, ['tab' => $validated['jenis']])
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Buat jenis kategori baru beserta kategori pertamanya.
     * Kode jenis di-generate otomatis dari label yang dimasukkan pengguna.
     */
    public function storeJenis(Request $request)
    {
        $validated = $request->validate([
            'label'      => 'required|string|max:100',
            'nama'       => 'required|string|max:150',
            'deskripsi'  => 'nullable|string',
        ]);

        // Auto-generate kode jenis dari label
        // "Mata Kuliah Khusus" → "mata_kuliah_khusus"
        $jenis = strtolower($validated['label']);
        $jenis = preg_replace('/\s+/', '_', $jenis);          // spasi → underscore
        $jenis = preg_replace('/[^a-z0-9_]/', '', $jenis);    // hapus karakter non-valid
        $jenis = preg_replace('/_+/', '_', $jenis);            // collapse multi-underscore
        $jenis = trim($jenis, '_');                            // hapus _ di awal/akhir
        $jenis = substr($jenis, 0, 48);                        // max panjang

        if (empty($jenis)) {
            $jenis = 'jenis';
        }

        // Handle keunikan: tambahkan angka jika sudah ada
        $baseJenis = $jenis;
        $counter   = 2;
        while (MasterKategori::where('jenis', $jenis)->exists()) {
            $jenis = $baseJenis . '_' . $counter;
            $counter++;
        }

        MasterKategori::create([
            'jenis'     => $jenis,
            'nama'      => $validated['nama'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'urutan'    => 1,
            'is_aktif'  => true,
        ]);

        $route = request()->routeIs('admin.*')
            ? 'admin.master-kategori.index'
            : 'kaprodi.master-kategori.index';

        return redirect()
            ->route($route, ['tab' => $jenis])
            ->with('success', 'Jenis kategori "' . $validated['label'] . '" berhasil dibuat.');
    }

    public function update(Request $request, MasterKategori $masterKategori)
    {
        $validated = $request->validate([
            'nama'      => 'required|string|max:150|unique:master_kategori,nama,' . $masterKategori->id . ',id,jenis,' . $masterKategori->jenis,
            'deskripsi' => 'nullable|string',
            'urutan'    => 'nullable|integer|min:0',
        ]);

        $masterKategori->update($validated);

        $route = request()->routeIs('admin.*')
            ? 'admin.master-kategori.index'
            : 'kaprodi.master-kategori.index';

        return redirect()
            ->route($route)
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function toggleAktif(MasterKategori $masterKategori)
    {
        $masterKategori->update(['is_aktif' => !$masterKategori->is_aktif]);

        $status = $masterKategori->is_aktif ? 'diaktifkan' : 'dinonaktifkan';

        $route = request()->routeIs('admin.*')
            ? 'admin.master-kategori.index'
            : 'kaprodi.master-kategori.index';

        return redirect()
            ->route($route)
            ->with('success', "Kategori \"{$masterKategori->nama}\" berhasil {$status}.");
    }
}
