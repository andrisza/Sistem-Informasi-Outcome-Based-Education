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
        $validated = $request->validate([
            'jenis'     => 'required|in:pl,cpl,bk,mk',
            'nama'      => 'required|string|max:150|unique:master_kategori,nama,NULL,id,jenis,' . $request->input('jenis'),
            'deskripsi' => 'nullable|string',
            'urutan'    => 'nullable|integer|min:0',
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
            ->route($route)
            ->with('success', 'Kategori berhasil ditambahkan.');
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
