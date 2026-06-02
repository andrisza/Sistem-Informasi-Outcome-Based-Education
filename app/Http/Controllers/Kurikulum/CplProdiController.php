<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\CplProdi;
use App\Models\Kurikulum;
use App\Models\MasterKategori;
use App\Traits\GeneratesObeKode;
use Illuminate\Http\Request;

class CplProdiController extends Controller
{
    use GeneratesObeKode;

    public function index(Kurikulum $kurikulum)
    {
        // Urutan kategori: Sikap → KU → KK → Pengetahuan
        $katOrder = ['Sikap' => 1, 'Keterampilan Umum' => 2, 'Keterampilan Khusus' => 3, 'Pengetahuan' => 4];

        $cplList = $kurikulum->cplProdi()
            ->orderByRaw("FIELD(kategori, 'Sikap', 'Keterampilan Umum', 'Keterampilan Khusus', 'Pengetahuan')")
            ->orderBy('urutan')
            ->get();

        return view('kurikulum.cpl-prodi.index', compact('kurikulum', 'cplList'));
    }

    public function create(Kurikulum $kurikulum)
    {
        $nextUrutan      = ($kurikulum->cplProdi()->max('urutan') ?? 0) + 1;
        $nextKode        = $this->generateKodeCpl($kurikulum);
        $kategoriOptions = MasterKategori::jenis('cpl')->aktif()->orderBy('urutan')->pluck('nama');

        return view('kurikulum.cpl-prodi.create', compact('kurikulum', 'nextUrutan', 'nextKode', 'kategoriOptions'));
    }

    public function store(Request $request, Kurikulum $kurikulum)
    {
        $validated = $request->validate([
            'kode_cpl'  => 'nullable|string|max:50|unique:cpl_prodi,kode_cpl,NULL,id,id_kurikulum,' . $kurikulum->id,
            'deskripsi' => 'required|string',
            'kategori'  => 'required|string|max:100',
            'referensi' => 'nullable|string',
        ]);

        if (empty($validated['kode_cpl'])) {
            $validated['kode_cpl'] = $this->generateKodeCpl($kurikulum);
        }

        $validated['urutan']       = ($kurikulum->cplProdi()->max('urutan') ?? 0) + 1;
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
        $kategoriOptions = MasterKategori::jenis('cpl')->aktif()->orderBy('urutan')->pluck('nama');
        return view('kurikulum.cpl-prodi.edit', compact('kurikulum', 'cplProdi', 'kategoriOptions'));
    }

    public function update(Request $request, Kurikulum $kurikulum, CplProdi $cplProdi)
    {
        $validated = $request->validate([
            'kode_cpl'  => 'required|string|max:50',
            'deskripsi' => 'required|string',
            'kategori'  => 'required|string|max:100',
            'referensi' => 'nullable|string',
        ]);

        $cplProdi->update($validated);

        return redirect()
            ->route('kurikulum.cpl-prodi.index', $kurikulum)
            ->with('success', 'CPL Prodi berhasil diperbarui.');
    }

    public function destroy(Kurikulum $kurikulum, CplProdi $cplProdi)
    {
        $cplProdi->delete();

        $remaining = $kurikulum->cplProdi()->orderBy('id')->get();
        foreach ($remaining as $i => $item) {
            $item->urutan = $i + 1;
            $item->save();
        }

        return redirect()
            ->route('kurikulum.cpl-prodi.index', $kurikulum)
            ->with('success', 'CPL Prodi berhasil dihapus.');
    }

    private function generateKodeCpl(Kurikulum $kurikulum): string
    {
        // Format: CPL{nn} — contoh: CPL01, CPL02, CPL14
        $count = $kurikulum->cplProdi()->withTrashed()->count() + 1;
        return 'CPL' . str_pad($count, 2, '0', STR_PAD_LEFT);
    }
}
