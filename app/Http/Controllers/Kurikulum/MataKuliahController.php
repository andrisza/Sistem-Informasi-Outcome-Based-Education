<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Kurikulum;
use App\Models\MataKuliah;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    public function index(Kurikulum $kurikulum)
    {
        $mkList = $kurikulum->mataKuliah()
            ->withCount('cpmk')
            ->orderBy('semester')
            ->orderBy('kode_mk')
            ->get();

        $bySmester = $mkList->groupBy('semester');

        return view('kurikulum.mata-kuliah.index', compact('kurikulum', 'mkList', 'bySmester'));
    }

    public function create(Kurikulum $kurikulum)
    {
        $kategoriOptions = ['Wajib', 'Pilihan', 'MKWK', 'MKDU'];
        return view('kurikulum.mata-kuliah.create', compact('kurikulum', 'kategoriOptions'));
    }

    public function store(Request $request, Kurikulum $kurikulum)
    {
        $validated = $request->validate([
            'kode_mk'      => 'required|string|max:50',
            'nama_mk'      => 'required|string|max:255',
            'sks_teori'    => 'required|integer|min:0',
            'sks_praktikum'=> 'required|integer|min:0',
            'semester'     => 'required|integer|min:1|max:14',
            'kategori_mk'  => 'required|in:Wajib,Pilihan,MKWK,MKDU',
        ]);

        $validated['id_kurikulum'] = $kurikulum->id;
        // sks_total is a MySQL generated column (sks_teori + sks_praktikum) — must NOT be set explicitly

        $mk = MataKuliah::create($validated);
        // Observer MataKuliahObserver::saved() akan otomatis sync distribusi_semester

        ActivityLog::record('create', MataKuliah::class, $mk->id, [], $mk->only(['kode_mk', 'nama_mk', 'semester', 'sks_teori', 'sks_praktikum']));

        return redirect()
            ->route('kurikulum.mata-kuliah.index', $kurikulum)
            ->with('success', 'Mata Kuliah berhasil ditambahkan.');
    }

    public function show(Kurikulum $kurikulum, MataKuliah $mataKuliah)
    {
        $mataKuliah->load(['cpmk.cplProdi', 'cpmk.subCpmk']);

        return view('kurikulum.mata-kuliah.show', compact('kurikulum', 'mataKuliah'));
    }

    public function edit(Kurikulum $kurikulum, MataKuliah $mataKuliah)
    {
        $kategoriOptions = ['Wajib', 'Pilihan', 'MKWK', 'MKDU'];
        return view('kurikulum.mata-kuliah.edit', compact('kurikulum', 'mataKuliah', 'kategoriOptions'));
    }

    public function update(Request $request, Kurikulum $kurikulum, MataKuliah $mataKuliah)
    {
        $validated = $request->validate([
            'kode_mk'      => 'required|string|max:50',
            'nama_mk'      => 'required|string|max:255',
            'sks_teori'    => 'required|integer|min:0',
            'sks_praktikum'=> 'required|integer|min:0',
            'semester'     => 'required|integer|min:1|max:14',
            'kategori_mk'  => 'required|in:Wajib,Pilihan,MKWK,MKDU',
        ]);

        // sks_total is a MySQL generated column — must NOT be set explicitly
        $old = $mataKuliah->only(array_keys($validated));
        $mataKuliah->update($validated);
        // Observer MataKuliahObserver::saved() akan otomatis sync distribusi_semester

        ActivityLog::record('update', MataKuliah::class, $mataKuliah->id, $old, $validated);

        return redirect()
            ->route('kurikulum.mata-kuliah.index', $kurikulum)
            ->with('success', 'Mata Kuliah berhasil diperbarui.');
    }

    public function destroy(Kurikulum $kurikulum, MataKuliah $mataKuliah)
    {
        $old = $mataKuliah->only(['kode_mk', 'nama_mk', 'semester']);

        $mataKuliah->delete();
        // Observer MataKuliahObserver::deleted() akan otomatis sync distribusi_semester

        ActivityLog::record('delete', MataKuliah::class, $mataKuliah->id, $old, []);

        return redirect()
            ->route('kurikulum.mata-kuliah.index', $kurikulum)
            ->with('success', 'Mata Kuliah berhasil dihapus.');
    }
}
