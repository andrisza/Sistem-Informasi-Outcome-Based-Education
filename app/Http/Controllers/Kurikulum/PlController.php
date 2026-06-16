<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\Kurikulum;
use App\Models\MasterKategori;
use App\Models\Pl;
use App\Services\ExcelExportService;
use App\Traits\GeneratesObeKode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PlController extends Controller
{
    use GeneratesObeKode;

    public function index(Kurikulum $kurikulum)
    {
        $plList = $kurikulum->pl()
            ->orderBy('urutan')
            ->get();

        return view('kurikulum.pl.index', compact('kurikulum', 'plList'));
    }

    public function create(Kurikulum $kurikulum)
    {
        $nextUrutan      = ($kurikulum->pl()->max('urutan') ?? 0) + 1;
        $nextKode        = $this->generateKodePl($kurikulum);
        $kategoriOptions = MasterKategori::jenis('pl')->aktif()->orderBy('urutan')->pluck('nama');

        return view('kurikulum.pl.create', compact('kurikulum', 'nextUrutan', 'nextKode', 'kategoriOptions'));
    }

    public function store(Request $request, Kurikulum $kurikulum)
    {
        $validated = $request->validate([
            'kode_pl'  => 'nullable|string|max:50|unique:pl,kode_pl,NULL,id,id_kurikulum,' . $kurikulum->id,
            'deskripsi' => 'required|string',
            'kategori'  => 'nullable|string|max:100',
            'referensi' => 'nullable|string',
        ]);

        if (empty($validated['kode_pl'])) {
            $validated['kode_pl'] = $this->generateKodePl($kurikulum);
        }

        $validated['urutan']       = ($kurikulum->pl()->max('urutan') ?? 0) + 1;
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
        $kategoriOptions = MasterKategori::jenis('pl')->aktif()->orderBy('urutan')->pluck('nama');
        return view('kurikulum.pl.edit', compact('kurikulum', 'pl', 'kategoriOptions'));
    }

    public function update(Request $request, Kurikulum $kurikulum, Pl $pl)
    {
        $validated = $request->validate([
            'kode_pl'   => 'required|string|max:50',
            'deskripsi' => 'required|string',
            'kategori'  => 'nullable|string|max:100',
            'referensi' => 'nullable|string',
        ]);

        $pl->update($validated);

        return redirect()
            ->route('kurikulum.pl.index', $kurikulum)
            ->with('success', 'Profil Lulusan berhasil diperbarui.');
    }

    public function destroy(Kurikulum $kurikulum, Pl $pl)
    {
        $pl->delete();

        $remaining = $kurikulum->pl()->orderBy('id')->get();
        foreach ($remaining as $i => $item) {
            $item->urutan = $i + 1;
            $item->save();
        }

        return redirect()
            ->route('kurikulum.pl.index', $kurikulum)
            ->with('success', 'Profil Lulusan berhasil dihapus.');
    }

    public function approve(Kurikulum $kurikulum, Pl $pl)
    {
        Gate::authorize('arsip-kurikulum');

        $pl->update([
            'status'      => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()
            ->route('kurikulum.pl.index', $kurikulum)
            ->with('success', "PL {$pl->kode_pl} berhasil disetujui.");
    }

    public function batchApprove(Request $request, Kurikulum $kurikulum)
    {
        Gate::authorize('arsip-kurikulum');

        $ids = array_filter(array_map('intval', $request->input('ids', [])));
        if (empty($ids)) {
            return back()->with('error', 'Pilih setidaknya satu PL.');
        }

        $count = $kurikulum->pl()
            ->whereIn('id', $ids)
            ->where('status', 'draft')
            ->update(['status' => 'approved', 'approved_by' => Auth::id(), 'approved_at' => now()]);

        return back()->with('success', "$count PL berhasil disetujui.");
    }

    public function export(Kurikulum $kurikulum, ExcelExportService $excel)
    {
        $plList = $kurikulum->pl()->orderBy('urutan')->get();

        $headerRow = [
            ['label' => 'No', 'bg' => 'F59E0B'],
            ['label' => 'Kode PL', 'bg' => 'F59E0B'],
            ['label' => 'Profil Lulusan (PL)', 'bg' => 'F59E0B'],
            ['label' => 'Kategori', 'bg' => 'F59E0B'],
            ['label' => 'Referensi', 'bg' => 'F59E0B'],
            ['label' => 'Status', 'bg' => 'F59E0B'],
        ];

        $rows = $plList->map(fn ($pl, $i) => [
            $i + 1,
            $pl->kode_pl,
            $pl->deskripsi,
            $pl->kategori,
            $pl->referensi,
            ucfirst($pl->status ?? 'draft'),
        ])->all();

        return $excel->download("profil-lulusan-{$kurikulum->kode}.xlsx", [
            'Profil Lulusan' => [
                'headerRows' => [$headerRow],
                'rows'       => $rows,
                'colWidths'  => [5, 14, 60, 22, 30, 12],
            ],
        ]);
    }

    private function generateKodePl(Kurikulum $kurikulum): string
    {
        // Format: PL{nn} — contoh: PL01, PL02, PL10
        $count = $kurikulum->pl()->withTrashed()->count() + 1;
        return 'PL' . str_pad($count, 2, '0', STR_PAD_LEFT);
    }
}
