<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\Cpmk;
use App\Models\Kurikulum;
use App\Models\MataKuliah;
use Illuminate\Http\Request;

class CpmkController extends Controller
{
    public function index(Kurikulum $kurikulum, MataKuliah $mataKuliah)
    {
        $cpmkList = $mataKuliah->cpmk()
            ->with('cplProdi')
            ->withCount('subCpmk')
            ->orderBy('urutan')
            ->get();

        return view('kurikulum.cpmk.index', compact('kurikulum', 'mataKuliah', 'cpmkList'));
    }

    public function create(Kurikulum $kurikulum, MataKuliah $mataKuliah)
    {
        $cplList    = $kurikulum->cplProdi()->orderBy('urutan')->get();
        $nextUrutan = ($mataKuliah->cpmk()->max('urutan') ?? 0) + 1;

        return view('kurikulum.cpmk.create', compact('kurikulum', 'mataKuliah', 'cplList', 'nextUrutan'));
    }

    public function store(Request $request, Kurikulum $kurikulum, MataKuliah $mataKuliah)
    {
        $validated = $request->validate([
            'id_cpl'     => 'required|exists:cpl_prodi,id',
            'kode_cpmk'  => 'required|string|max:50',
            'deskripsi'  => 'required|string',
            'urutan'     => 'required|integer|min:1',
        ]);

        $validated['id_kurikulum'] = $kurikulum->id;
        $validated['id_mk']        = $mataKuliah->id;

        Cpmk::create($validated);

        return redirect()
            ->route('kurikulum.mata-kuliah.cpmk.index', [$kurikulum, $mataKuliah])
            ->with('success', 'CPMK berhasil ditambahkan.');
    }

    public function show(Kurikulum $kurikulum, MataKuliah $mataKuliah, Cpmk $cpmk)
    {
        $cpmk->load(['cplProdi', 'subCpmk']);
        return view('kurikulum.cpmk.show', compact('kurikulum', 'mataKuliah', 'cpmk'));
    }

    public function edit(Kurikulum $kurikulum, MataKuliah $mataKuliah, Cpmk $cpmk)
    {
        $cplList = $kurikulum->cplProdi()->orderBy('urutan')->get();
        return view('kurikulum.cpmk.edit', compact('kurikulum', 'mataKuliah', 'cpmk', 'cplList'));
    }

    public function update(Request $request, Kurikulum $kurikulum, MataKuliah $mataKuliah, Cpmk $cpmk)
    {
        $validated = $request->validate([
            'id_cpl'     => 'required|exists:cpl_prodi,id',
            'kode_cpmk'  => 'required|string|max:50',
            'deskripsi'  => 'required|string',
            'urutan'     => 'required|integer|min:1',
        ]);

        $cpmk->update($validated);

        return redirect()
            ->route('kurikulum.mata-kuliah.cpmk.index', [$kurikulum, $mataKuliah])
            ->with('success', 'CPMK berhasil diperbarui.');
    }

    public function destroy(Kurikulum $kurikulum, MataKuliah $mataKuliah, Cpmk $cpmk)
    {
        $cpmk->delete();

        return redirect()
            ->route('kurikulum.mata-kuliah.cpmk.index', [$kurikulum, $mataKuliah])
            ->with('success', 'CPMK berhasil dihapus.');
    }
}
