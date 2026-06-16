<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\CplProdi;
use App\Models\Kurikulum;
use App\Services\ExcelExportService;
use App\Traits\GeneratesObeKode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CplProdiController extends Controller
{
    use GeneratesObeKode;

    public function index(Kurikulum $kurikulum)
    {
        $cplList = $kurikulum->cplProdi()
            ->orderBy('urutan')
            ->get();

        return view('kurikulum.cpl-prodi.index', compact('kurikulum', 'cplList'));
    }

    public function create(Kurikulum $kurikulum)
    {
        $nextUrutan = ($kurikulum->cplProdi()->max('urutan') ?? 0) + 1;
        $nextKode   = $this->generateKodeCpl($kurikulum);

        return view('kurikulum.cpl-prodi.create', compact('kurikulum', 'nextUrutan', 'nextKode'));
    }

    public function store(Request $request, Kurikulum $kurikulum)
    {
        $validated = $request->validate([
            'kode_cpl'  => 'nullable|string|max:50|unique:cpl_prodi,kode_cpl,NULL,id,id_kurikulum,' . $kurikulum->id,
            'deskripsi' => 'required|string',
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
        return view('kurikulum.cpl-prodi.edit', compact('kurikulum', 'cplProdi'));
    }

    public function update(Request $request, Kurikulum $kurikulum, CplProdi $cplProdi)
    {
        $validated = $request->validate([
            'deskripsi' => 'required|string',
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

    public function export(Kurikulum $kurikulum, ExcelExportService $excel)
    {
        $cplList = $kurikulum->cplProdi()->orderBy('urutan')->get();

        $headerRow = [
            ['label' => 'No', 'bg' => '7C3AED'],
            ['label' => 'Kode CPL', 'bg' => '7C3AED'],
            ['label' => 'Deskripsi CPL', 'bg' => '7C3AED'],
            ['label' => 'Referensi SN-Dikti', 'bg' => '7C3AED'],
            ['label' => 'Status', 'bg' => '7C3AED'],
        ];

        $rows = $cplList->map(fn ($cpl, $i) => [
            $i + 1,
            $cpl->kode_cpl,
            $cpl->deskripsi,
            $cpl->referensi,
            ucfirst($cpl->status ?? 'draft'),
        ])->all();

        return $excel->download("cpl-prodi-{$kurikulum->kode}.xlsx", [
            'CPL Prodi' => [
                'headerRows' => [$headerRow],
                'rows'       => $rows,
                'colWidths'  => [5, 14, 60, 30, 12],
            ],
        ]);
    }

    public function approve(Kurikulum $kurikulum, CplProdi $cplProdi)
    {
        Gate::authorize('arsip-kurikulum');

        $cplProdi->update([
            'status'      => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()
            ->route('kurikulum.cpl-prodi.index', $kurikulum)
            ->with('success', "CPL {$cplProdi->kode_cpl} berhasil disetujui.");
    }

    public function batchApprove(Request $request, Kurikulum $kurikulum)
    {
        Gate::authorize('arsip-kurikulum');

        $ids = array_filter(array_map('intval', $request->input('ids', [])));
        if (empty($ids)) {
            return back()->with('error', 'Pilih setidaknya satu CPL.');
        }

        $count = $kurikulum->cplProdi()
            ->whereIn('id', $ids)
            ->where('status', 'draft')
            ->update(['status' => 'approved', 'approved_by' => Auth::id(), 'approved_at' => now()]);

        return back()->with('success', "$count CPL Prodi berhasil disetujui.");
    }

    private function generateKodeCpl(Kurikulum $kurikulum): string
    {
        // Format: CPL{nn} — contoh: CPL01, CPL02, CPL14
        $count = $kurikulum->cplProdi()->withTrashed()->count() + 1;
        return 'CPL' . str_pad($count, 2, '0', STR_PAD_LEFT);
    }
}
