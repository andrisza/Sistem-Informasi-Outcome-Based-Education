<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\Kurikulum;
use App\Models\Pl;
use Illuminate\Http\Request;

class PlController extends Controller
{
    public function index(Kurikulum $kurikulum)
    {
        $plList = $kurikulum->pl()
            ->withCount('cplProdi')
            ->orderBy('urutan')
            ->get();

        return view('kurikulum.pl.index', compact('kurikulum', 'plList'));
    }

    public function create(Kurikulum $kurikulum)
    {
        $nextUrutan = ($kurikulum->pl()->max('urutan') ?? 0) + 1;
        return view('kurikulum.pl.create', compact('kurikulum', 'nextUrutan'));
    }

    public function store(Request $request, Kurikulum $kurikulum)
    {
        $validated = $request->validate([
            'kode_pl'         => 'required|string|max:50',
            'deskripsi'       => 'required|string',
            'kategori'        => 'nullable|string|max:100',
            'ref_area_fungsi_1' => 'nullable|string|max:255',
            'ref_area_fungsi_2' => 'nullable|string|max:255',
            'ref_area_fungsi_3' => 'nullable|string|max:255',
            'urutan'          => 'required|integer|min:1',
        ]);

        $validated['id_kurikulum'] = $kurikulum->id;
        Pl::create($validated);

        return redirect()
            ->route('kurikulum.pl.index', $kurikulum)
            ->with('success', 'Profil Lulusan berhasil ditambahkan.');
    }

    public function show(Kurikulum $kurikulum, Pl $pl)
    {
        $pl->load('cplProdi');
        return view('kurikulum.pl.show', compact('kurikulum', 'pl'));
    }

    public function edit(Kurikulum $kurikulum, Pl $pl)
    {
        return view('kurikulum.pl.edit', compact('kurikulum', 'pl'));
    }

    public function update(Request $request, Kurikulum $kurikulum, Pl $pl)
    {
        $validated = $request->validate([
            'kode_pl'         => 'required|string|max:50',
            'deskripsi'       => 'required|string',
            'kategori'        => 'nullable|string|max:100',
            'ref_area_fungsi_1' => 'nullable|string|max:255',
            'ref_area_fungsi_2' => 'nullable|string|max:255',
            'ref_area_fungsi_3' => 'nullable|string|max:255',
            'urutan'          => 'required|integer|min:1',
        ]);

        $pl->update($validated);

        return redirect()
            ->route('kurikulum.pl.index', $kurikulum)
            ->with('success', 'Profil Lulusan berhasil diperbarui.');
    }

    public function destroy(Kurikulum $kurikulum, Pl $pl)
    {
        $pl->delete();

        return redirect()
            ->route('kurikulum.pl.index', $kurikulum)
            ->with('success', 'Profil Lulusan berhasil dihapus.');
    }
}
