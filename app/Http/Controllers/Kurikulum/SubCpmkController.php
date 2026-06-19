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
        $nextUrutan = ($cpmk->subCpmk()->max('urutan') ?? 0) + 1;
        $totalBobot = $cpmk->subCpmk()->sum('bobot');
        $nextKode   = $this->generateKodeSubCpmk($cpmk);

        return view('kurikulum.sub-cpmk.create', compact('kurikulum', 'mataKuliah', 'cpmk', 'nextUrutan', 'totalBobot', 'nextKode'));
    }

    public function store(Request $request, Kurikulum $kurikulum, MataKuliah $mataKuliah, Cpmk $cpmk)
    {
        $validated = $request->validate([
            'kode_sub_cpmk' => 'nullable|string|max:50',
            'deskripsi'     => 'required|string',
            'bobot'         => 'nullable|numeric|min:0|max:100',
        ]);

        $validated['bobot'] ??= 0;

        if (empty($validated['kode_sub_cpmk'])) {
            $validated['kode_sub_cpmk'] = $this->generateKodeSubCpmk($cpmk);
        }

        $validated['urutan']  = ($cpmk->subCpmk()->max('urutan') ?? 0) + 1;
        $validated['id_cpmk'] = $cpmk->id;

        SubCpmk::create($validated);

        $redirectTo = $request->input('_redirect_to');
        return $redirectTo
            ? redirect($redirectTo)->with('success', 'Sub-CPMK berhasil ditambahkan.')
            : redirect()->route('kurikulum.mata-kuliah.cpmk.sub-cpmk.index', [$kurikulum, $mataKuliah, $cpmk])->with('success', 'Sub-CPMK berhasil ditambahkan.');
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
        ]);

        $subCpmk->update($validated);

        $redirectTo = $request->input('_redirect_to');
        return $redirectTo
            ? redirect($redirectTo)->with('success', 'Sub-CPMK berhasil diperbarui.')
            : redirect()->route('kurikulum.mata-kuliah.cpmk.sub-cpmk.index', [$kurikulum, $mataKuliah, $cpmk])->with('success', 'Sub-CPMK berhasil diperbarui.');
    }

    public function destroy(Request $request, Kurikulum $kurikulum, MataKuliah $mataKuliah, Cpmk $cpmk, SubCpmk $subCpmk)
    {
        $subCpmk->delete();

        $remaining = $cpmk->subCpmk()->orderBy('id')->get();
        foreach ($remaining as $i => $item) {
            $item->urutan = $i + 1;
            $item->save();
        }

        $redirectTo = $request->input('_redirect_to');
        return $redirectTo
            ? redirect($redirectTo)->with('success', 'Sub-CPMK berhasil dihapus.')
            : redirect()->route('kurikulum.mata-kuliah.cpmk.sub-cpmk.index', [$kurikulum, $mataKuliah, $cpmk])->with('success', 'Sub-CPMK berhasil dihapus.');
    }

    /**
     * Generate kode Sub-CPMK sesuai format diagram:
     * Sub-CPMK{cpl_nn}{cpmk_n}{sub_n}
     * Contoh: Sub-CPMK0111 = CPL-01, CPMK-1, Sub ke-1
     *
     * Parse dari kode parent CPMK format CPMK{nn}{n} → CPMK011 → cpl=01, cpmk=1
     */
    private function generateKodeSubCpmk(Cpmk $cpmk): string
    {
        // Parse kode CPMK: CPMK{cpl_2digit}{cpmk_1digit} → e.g., CPMK011
        preg_match('/^CPMK(\d{2})(\d+)$/', $cpmk->kode_cpmk, $m);
        $cplPart  = $m[1] ?? '01';
        $cpmkPart = $m[2] ?? '1';

        $subCount = SubCpmk::where('id_cpmk', $cpmk->id)->count() + 1;

        // Format: Sub-CPMK{cpl_2digit}{cpmk_1digit}{sub_1digit} → Sub-CPMK0111
        return 'Sub-CPMK' . $cplPart . $cpmkPart . $subCount;
    }
}
