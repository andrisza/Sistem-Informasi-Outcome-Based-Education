<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\CplProdi;
use App\Models\Kurikulum;
use Illuminate\Http\Request;

class CplProdiController extends Controller
{
    public function index(Kurikulum $kurikulum)
    {
        $cplList = $kurikulum->cplProdi()
            ->withCount('cpmk')
            ->orderBy('urutan')
            ->get();

        return view('kurikulum.cpl-prodi.index', compact('kurikulum', 'cplList'));
    }

    public function create(Kurikulum $kurikulum)
    {
        $nextUrutan = ($kurikulum->cplProdi()->max('urutan') ?? 0) + 1;
        $kategoriOptions = ['Sikap', 'Keterampilan Umum', 'Keterampilan Khusus', 'Pengetahuan'];

        return view('kurikulum.cpl-prodi.create', compact('kurikulum', 'nextUrutan', 'kategoriOptions'));
    }

    public function store(Request $request, Kurikulum $kurikulum)
    {
        $validated = $request->validate([
            'kode_cpl'  => 'required|string|max:50',
            'deskripsi' => 'required|string',
            'kategori'  => 'required|in:Sikap,Keterampilan Umum,Keterampilan Khusus,Pengetahuan',
            'urutan'    => 'required|integer|min:1',
        ]);

        $validated['id_kurikulum'] = $kurikulum->id;
        CplProdi::create($validated);

        return redirect()
            ->route('kurikulum.cpl-prodi.index', $kurikulum)
            ->with('success', 'CPL Prodi berhasil ditambahkan.');
    }

    public function show(Kurikulum $kurikulum, CplProdi $cplProdi)
    {
        $cplProdi->load(['cpmk.mataKuliah']);
        return view('kurikulum.cpl-prodi.show', compact('kurikulum', 'cplProdi'));
    }

    public function edit(Kurikulum $kurikulum, CplProdi $cplProdi)
    {
        $kategoriOptions = ['Sikap', 'Keterampilan Umum', 'Keterampilan Khusus', 'Pengetahuan'];
        return view('kurikulum.cpl-prodi.edit', compact('kurikulum', 'cplProdi', 'kategoriOptions'));
    }

    public function update(Request $request, Kurikulum $kurikulum, CplProdi $cplProdi)
    {
        $validated = $request->validate([
            'kode_cpl'  => 'required|string|max:50',
            'deskripsi' => 'required|string',
            'kategori'  => 'required|in:Sikap,Keterampilan Umum,Keterampilan Khusus,Pengetahuan',
            'urutan'    => 'required|integer|min:1',
        ]);

        $cplProdi->update($validated);

        return redirect()
            ->route('kurikulum.cpl-prodi.index', $kurikulum)
            ->with('success', 'CPL Prodi berhasil diperbarui.');
    }

    public function destroy(Kurikulum $kurikulum, CplProdi $cplProdi)
    {
        $cplProdi->delete();

        return redirect()
            ->route('kurikulum.cpl-prodi.index', $kurikulum)
            ->with('success', 'CPL Prodi berhasil dihapus.');
    }
}
