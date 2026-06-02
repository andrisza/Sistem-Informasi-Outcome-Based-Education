<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\Cpmk;
use App\Models\CplProdi;
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
        $nextKode   = $this->generateKodeCpmk($mataKuliah);

        return view('kurikulum.cpmk.create', compact('kurikulum', 'mataKuliah', 'cplList', 'nextUrutan', 'nextKode'));
    }

    public function store(Request $request, Kurikulum $kurikulum, MataKuliah $mataKuliah)
    {
        $bloomOptions = ['C1','C2','C3','C4','C5','C6','A1','A2','A3','A4','A5','P1','P2','P3','P4','P5'];

        $validated = $request->validate([
            'id_cpl'      => 'required|exists:cpl_prodi,id',
            'kode_cpmk'   => 'nullable|string|max:50',
            'deskripsi'   => 'required|string',
            'level_bloom' => 'nullable|in:' . implode(',', $bloomOptions),
        ]);

        if (empty($validated['kode_cpmk'])) {
            $cpl = CplProdi::find($validated['id_cpl']);
            $validated['kode_cpmk'] = $this->generateKodeCpmk($mataKuliah, $cpl);
        }

        $validated['urutan']       = ($mataKuliah->cpmk()->max('urutan') ?? 0) + 1;
        $validated['id_kurikulum'] = $kurikulum->id;
        $validated['id_mk']        = $mataKuliah->id;

        Cpmk::create($validated);

        $redirectTo = $request->input('_redirect_to');
        return $redirectTo
            ? redirect($redirectTo)->with('success', 'CPMK berhasil ditambahkan.')
            : redirect()->route('kurikulum.mata-kuliah.cpmk.index', [$kurikulum, $mataKuliah])->with('success', 'CPMK berhasil ditambahkan.');
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
        $bloomOptions = ['C1','C2','C3','C4','C5','C6','A1','A2','A3','A4','A5','P1','P2','P3','P4','P5'];

        $validated = $request->validate([
            'id_cpl'      => 'required|exists:cpl_prodi,id',
            'kode_cpmk'   => 'required|string|max:50',
            'deskripsi'   => 'required|string',
            'level_bloom' => 'nullable|in:' . implode(',', $bloomOptions),
        ]);

        $cpmk->update($validated);

        $redirectTo = $request->input('_redirect_to');
        return $redirectTo
            ? redirect($redirectTo)->with('success', 'CPMK berhasil diperbarui.')
            : redirect()->route('kurikulum.mata-kuliah.cpmk.index', [$kurikulum, $mataKuliah])->with('success', 'CPMK berhasil diperbarui.');
    }

    public function destroy(Request $request, Kurikulum $kurikulum, MataKuliah $mataKuliah, Cpmk $cpmk)
    {
        $cpmk->delete();

        $remaining = $mataKuliah->cpmk()->orderBy('id')->get();
        foreach ($remaining as $i => $item) {
            $item->urutan = $i + 1;
            $item->save();
        }

        $redirectTo = $request->input('_redirect_to');
        return $redirectTo
            ? redirect($redirectTo)->with('success', 'CPMK berhasil dihapus.')
            : redirect()->route('kurikulum.mata-kuliah.cpmk.index', [$kurikulum, $mataKuliah])->with('success', 'CPMK berhasil dihapus.');
    }

    /**
     * Generate kode CPMK sesuai format diagram:
     * CPMK{cpl_nn}{cpmk_n}
     * Contoh: CPMK011 = CPL urutan-01, CPMK ke-1 pada MK tersebut
     */
    private function generateKodeCpmk(MataKuliah $mataKuliah, ?CplProdi $cpl = null): string
    {
        $cplSeq    = str_pad($cpl?->urutan ?? 0, 2, '0', STR_PAD_LEFT); // "01", "02", ...
        $cpmkCount = Cpmk::where('id_mk', $mataKuliah->id)
                         ->where('id_cpl', $cpl?->id)
                         ->withTrashed()->count() + 1;
        // Format: CPMK{cpl_2digit}{cpmk_1digit} → CPMK011
        return 'CPMK' . $cplSeq . $cpmkCount;
    }
}
