<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\BahanKajian;
use App\Models\Kurikulum;
use App\Models\MasterKategori;
use App\Traits\GeneratesObeKode;
use Illuminate\Http\Request;

class BahanKajianController extends Controller
{
    use GeneratesObeKode;

    public function index(Kurikulum $kurikulum)
    {
        $bkList = $kurikulum->bahanKajian()
            ->orderBy('urutan')
            ->get();

        return view('kurikulum.bahan-kajian.index', compact('kurikulum', 'bkList'));
    }

    public function create(Kurikulum $kurikulum)
    {
        $nextUrutan      = ($kurikulum->bahanKajian()->max('urutan') ?? 0) + 1;
        $nextKode        = $this->generateKodeBk($kurikulum);
        $bidangOptions   = MasterKategori::jenis('bk')->aktif()->orderBy('urutan')->pluck('nama');

        return view('kurikulum.bahan-kajian.create', compact('kurikulum', 'nextUrutan', 'nextKode', 'bidangOptions'));
    }

    public function store(Request $request, Kurikulum $kurikulum)
    {
        $validated = $request->validate([
            'kode_bk'         => 'nullable|string|max:50|unique:bahan_kajian,kode_bk,NULL,id,id_kurikulum,' . $kurikulum->id,
            'nama_bk'         => 'required|string|max:255',
            'deskripsi'       => 'nullable|string',
            'kompetensi'      => 'nullable|in:Utama,Pendukung,Umum',
            'referensi'       => 'nullable|string|max:150',
            'bidang_keilmuan' => 'nullable|string|max:150',
        ]);

        if (empty($validated['kode_bk'])) {
            $validated['kode_bk'] = $this->generateKodeBk($kurikulum);
        }

        $validated['urutan']       = ($kurikulum->bahanKajian()->max('urutan') ?? 0) + 1;
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
        $bidangOptions = MasterKategori::jenis('bk')->aktif()->orderBy('urutan')->pluck('nama');
        return view('kurikulum.bahan-kajian.edit', compact('kurikulum', 'bahanKajian', 'bidangOptions'));
    }

    public function update(Request $request, Kurikulum $kurikulum, BahanKajian $bahanKajian)
    {
        $validated = $request->validate([
            'kode_bk'         => 'required|string|max:50',
            'nama_bk'         => 'required|string|max:255',
            'deskripsi'       => 'nullable|string',
            'kompetensi'      => 'nullable|in:Utama,Pendukung,Umum',
            'referensi'       => 'nullable|string|max:150',
            'bidang_keilmuan' => 'nullable|string|max:150',
        ]);

        $bahanKajian->update($validated);

        return redirect()
            ->route('kurikulum.bahan-kajian.index', $kurikulum)
            ->with('success', 'Bahan Kajian berhasil diperbarui.');
    }

    public function destroy(Kurikulum $kurikulum, BahanKajian $bahanKajian)
    {
        $bahanKajian->delete();

        $remaining = $kurikulum->bahanKajian()->orderBy('id')->get();
        foreach ($remaining as $i => $item) {
            $item->urutan = $i + 1;
            $item->save();
        }

        return redirect()
            ->route('kurikulum.bahan-kajian.index', $kurikulum)
            ->with('success', 'Bahan Kajian berhasil dihapus.');
    }

    private function generateKodeBk(Kurikulum $kurikulum): string
    {
        // Format: BK{nn} — contoh: BK01, BK02, BK21
        $count = $kurikulum->bahanKajian()->withTrashed()->count() + 1;
        return 'BK' . str_pad($count, 2, '0', STR_PAD_LEFT);
    }
}
