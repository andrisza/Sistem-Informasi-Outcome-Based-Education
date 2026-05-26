<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\BahanKajian;
use App\Models\Kurikulum;
use Illuminate\Http\Request;

class BahanKajianController extends Controller
{
    public function index(Kurikulum $kurikulum)
    {
        $bkList = $kurikulum->bahanKajian()
            ->withCount(['cplProdi', 'mataKuliah'])
            ->orderBy('urutan')
            ->get();

        return view('kurikulum.bahan-kajian.index', compact('kurikulum', 'bkList'));
    }

    public function create(Kurikulum $kurikulum)
    {
        $nextUrutan = ($kurikulum->bahanKajian()->max('urutan') ?? 0) + 1;
        return view('kurikulum.bahan-kajian.create', compact('kurikulum', 'nextUrutan'));
    }

    public function store(Request $request, Kurikulum $kurikulum)
    {
        $validated = $request->validate([
            'kode_bk'   => 'required|string|max:50',
            'nama_bk'   => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'urutan'    => 'required|integer|min:1',
        ]);

        $validated['id_kurikulum'] = $kurikulum->id;
        BahanKajian::create($validated);

        return redirect()
            ->route('kurikulum.bahan-kajian.index', $kurikulum)
            ->with('success', 'Bahan Kajian berhasil ditambahkan.');
    }

    public function show(Kurikulum $kurikulum, BahanKajian $bahanKajian)
    {
        $bahanKajian->load(['cplProdi', 'mataKuliah']);
        return view('kurikulum.bahan-kajian.show', compact('kurikulum', 'bahanKajian'));
    }

    public function edit(Kurikulum $kurikulum, BahanKajian $bahanKajian)
    {
        return view('kurikulum.bahan-kajian.edit', compact('kurikulum', 'bahanKajian'));
    }

    public function update(Request $request, Kurikulum $kurikulum, BahanKajian $bahanKajian)
    {
        $validated = $request->validate([
            'kode_bk'   => 'required|string|max:50',
            'nama_bk'   => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'urutan'    => 'required|integer|min:1',
        ]);

        $bahanKajian->update($validated);

        return redirect()
            ->route('kurikulum.bahan-kajian.index', $kurikulum)
            ->with('success', 'Bahan Kajian berhasil diperbarui.');
    }

    public function destroy(Kurikulum $kurikulum, BahanKajian $bahanKajian)
    {
        $bahanKajian->delete();

        return redirect()
            ->route('kurikulum.bahan-kajian.index', $kurikulum)
            ->with('success', 'Bahan Kajian berhasil dihapus.');
    }
}
