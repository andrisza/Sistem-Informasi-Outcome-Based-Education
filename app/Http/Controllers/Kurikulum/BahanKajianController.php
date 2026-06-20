<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\BahanKajian;
use App\Models\Kurikulum;
use App\Models\MasterKategori;
use App\Services\ExcelExportService;
use App\Traits\GeneratesObeKode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class BahanKajianController extends Controller
{
    use GeneratesObeKode;

    /**
     * Daftar kompetensi aktif dari master_kategori (jenis 'bk').
     * $include opsional menambahkan nilai lama agar tetap valid saat edit.
     */
    private function kompetensiOptions(?string $include = null): array
    {
        $options = MasterKategori::jenis('bk')->aktif()->orderBy('urutan')->pluck('nama');

        if ($include && !$options->contains($include)) {
            $options->push($include);
        }

        return $options->all();
    }

    public function index(Kurikulum $kurikulum)
    {
        $bkList = $kurikulum->bahanKajian()
            ->orderBy('urutan')
            ->get();

        return view('kurikulum.bahan-kajian.index', compact('kurikulum', 'bkList'));
    }

    public function create(Kurikulum $kurikulum)
    {
        $nextUrutan        = ($kurikulum->bahanKajian()->max('urutan') ?? 0) + 1;
        $nextKode          = $this->generateKodeBk($kurikulum);
        $kompetensiOptions = $this->kompetensiOptions();

        return view('kurikulum.bahan-kajian.create', compact('kurikulum', 'nextUrutan', 'nextKode', 'kompetensiOptions'));
    }

    public function store(Request $request, Kurikulum $kurikulum)
    {
        $validated = $request->validate([
            'kode_bk'         => 'nullable|string|max:50|unique:bahan_kajian,kode_bk,NULL,id,id_kurikulum,' . $kurikulum->id,
            'nama_bk'         => 'required|string|max:255',
            'deskripsi'       => 'nullable|string',
            'kompetensi'      => ['nullable', Rule::in($this->kompetensiOptions())],
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
        $kompetensiOptions = $this->kompetensiOptions($bahanKajian->kompetensi);
        return view('kurikulum.bahan-kajian.edit', compact('kurikulum', 'bahanKajian', 'kompetensiOptions'));
    }

    public function update(Request $request, Kurikulum $kurikulum, BahanKajian $bahanKajian)
    {
        $validated = $request->validate([
            'nama_bk'         => 'required|string|max:255',
            'deskripsi'       => 'nullable|string',
            'kompetensi'      => ['nullable', Rule::in($this->kompetensiOptions($bahanKajian->kompetensi))],
            'referensi'       => 'nullable|string|max:150',
            'bidang_keilmuan' => 'nullable|string|max:150',
        ]);

        // kode_bk di-generate otomatis saat create dan tidak diubah saat edit.
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

    public function export(Kurikulum $kurikulum, ExcelExportService $excel)
    {
        $bkList = $kurikulum->bahanKajian()->orderBy('urutan')->get();

        $headerRow = [
            ['label' => 'No', 'bg' => 'F59E0B'],
            ['label' => 'Kode BK', 'bg' => 'F59E0B'],
            ['label' => 'Nama BK', 'bg' => 'F59E0B'],
            ['label' => 'Deskripsi', 'bg' => 'F59E0B'],
            ['label' => 'Kompetensi', 'bg' => 'F59E0B'],
            ['label' => 'Referensi', 'bg' => 'F59E0B'],
            ['label' => 'Status', 'bg' => 'F59E0B'],
        ];

        $rows = $bkList->map(fn ($bk, $i) => [
            $i + 1,
            $bk->kode_bk,
            $bk->nama_bk,
            $bk->deskripsi,
            $bk->kompetensi,
            $bk->referensi,
            ucfirst($bk->status ?? 'draft'),
        ])->all();

        return $excel->download("bahan-kajian-{$kurikulum->kode}.xlsx", [
            'Bahan Kajian' => [
                'headerRows' => [$headerRow],
                'rows'       => $rows,
                'colWidths'  => [5, 14, 30, 50, 14, 22, 12],
            ],
        ]);
    }

    public function approve(Kurikulum $kurikulum, BahanKajian $bahanKajian)
    {
        Gate::authorize('arsip-kurikulum');

        $bahanKajian->update([
            'status'      => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()
            ->route('kurikulum.bahan-kajian.index', $kurikulum)
            ->with('success', "Bahan Kajian {$bahanKajian->kode_bk} berhasil disetujui.");
    }

    public function batchApprove(Request $request, Kurikulum $kurikulum)
    {
        Gate::authorize('arsip-kurikulum');

        $ids = array_filter(array_map('intval', $request->input('ids', [])));
        if (empty($ids)) {
            return back()->with('error', 'Pilih setidaknya satu Bahan Kajian.');
        }

        $count = $kurikulum->bahanKajian()
            ->whereIn('id', $ids)
            ->where('status', 'draft')
            ->update(['status' => 'approved', 'approved_by' => Auth::id(), 'approved_at' => now()]);

        return back()->with('success', "$count Bahan Kajian berhasil disetujui.");
    }

    private function generateKodeBk(Kurikulum $kurikulum): string
    {
        // Format: BK{nn} — contoh: BK01, BK02, BK21
        $count = $kurikulum->bahanKajian()->withTrashed()->count() + 1;
        return 'BK' . str_pad($count, 2, '0', STR_PAD_LEFT);
    }
}
