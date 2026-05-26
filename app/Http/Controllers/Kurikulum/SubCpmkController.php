<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\Cpmk;
use App\Models\Kurikulum;
use App\Models\MataKuliah;
use App\Models\SubCpmk;
use Illuminate\Http\Request;

class SubCpmkController extends Controller
{
    public function index(Kurikulum $kurikulum, MataKuliah $mataKuliah, Cpmk $cpmk)
    {
        $subList = $cpmk->subCpmk()->orderBy('urutan')->get();

        return view('kurikulum.sub-cpmk.index', compact('kurikulum', 'mataKuliah', 'cpmk', 'subList'));
    }

    public function create(Kurikulum $kurikulum, MataKuliah $mataKuliah, Cpmk $cpmk)
    {
        $nextUrutan   = ($cpmk->subCpmk()->max('urutan') ?? 0) + 1;
        $totalBobot   = $cpmk->subCpmk()->sum('bobot');

        return view('kurikulum.sub-cpmk.create', compact('kurikulum', 'mataKuliah', 'cpmk', 'nextUrutan', 'totalBobot'));
    }

    public function store(Request $request, Kurikulum $kurikulum, MataKuliah $mataKuliah, Cpmk $cpmk)
    {
        $validated = $request->validate([
            'kode_sub_cpmk' => 'required|string|max:50',
            'deskripsi'     => 'required|string',
            'bobot'         => 'required|numeric|min:0|max:100',
            'urutan'        => 'required|integer|min:1',
        ]);

        $validated['id_cpmk'] = $cpmk->id;

        SubCpmk::create($validated);

        return redirect()
            ->route('kurikulum.mata-kuliah.cpmk.sub-cpmk.index', [$kurikulum, $mataKuliah, $cpmk])
            ->with('success', 'Sub-CPMK berhasil ditambahkan.');
    }

    public function show(Kurikulum $kurikulum, MataKuliah $mataKuliah, Cpmk $cpmk, SubCpmk $subCpmk)
    {
        return view('kurikulum.sub-cpmk.show', compact('kurikulum', 'mataKuliah', 'cpmk', 'subCpmk'));
    }

    public function edit(Kurikulum $kurikulum, MataKuliah $mataKuliah, Cpmk $cpmk, SubCpmk $subCpmk)
    {
        return view('kurikulum.sub-cpmk.edit', compact('kurikulum', 'mataKuliah', 'cpmk', 'subCpmk'));
    }

    public function update(Request $request, Kurikulum $kurikulum, MataKuliah $mataKuliah, Cpmk $cpmk, SubCpmk $subCpmk)
    {
        $validated = $request->validate([
            'kode_sub_cpmk' => 'required|string|max:50',
            'deskripsi'     => 'required|string',
            'bobot'         => 'required|numeric|min:0|max:100',
            'urutan'        => 'required|integer|min:1',
        ]);

        $subCpmk->update($validated);

        return redirect()
            ->route('kurikulum.mata-kuliah.cpmk.sub-cpmk.index', [$kurikulum, $mataKuliah, $cpmk])
            ->with('success', 'Sub-CPMK berhasil diperbarui.');
    }

    public function destroy(Kurikulum $kurikulum, MataKuliah $mataKuliah, Cpmk $cpmk, SubCpmk $subCpmk)
    {
        $subCpmk->delete();

        return redirect()
            ->route('kurikulum.mata-kuliah.cpmk.sub-cpmk.index', [$kurikulum, $mataKuliah, $cpmk])
            ->with('success', 'Sub-CPMK berhasil dihapus.');
    }
}
