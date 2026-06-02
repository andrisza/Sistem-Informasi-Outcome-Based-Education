<?php
namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\CplSndikti;
use App\Models\Kurikulum;
use Illuminate\Http\Request;

class CplSndiktiController extends Controller
{
    public function index(Kurikulum $kurikulum)
    {
        $cplsnList = CplSndikti::orderByRaw("FIELD(kategori,'Sikap','Keterampilan Umum','Keterampilan Khusus','Pengetahuan')")
            ->orderBy('urutan')
            ->get();
        return view('kurikulum.cpl-sndikti.index', compact('kurikulum', 'cplsnList'));
    }

    public function create(Kurikulum $kurikulum)
    {
        $kategoriOptions = ['Sikap', 'Keterampilan Umum', 'Keterampilan Khusus', 'Pengetahuan'];
        return view('kurikulum.cpl-sndikti.create', compact('kurikulum', 'kategoriOptions'));
    }

    public function store(Request $request, Kurikulum $kurikulum)
    {
        $validated = $request->validate([
            'kategori'  => 'required|in:Sikap,Keterampilan Umum,Keterampilan Khusus,Pengetahuan',
            'deskripsi' => 'required|string',
        ]);

        $prefix = match($validated['kategori']) {
            'Sikap'               => 'CPL-S',
            'Keterampilan Umum'   => 'CPL-KU',
            'Keterampilan Khusus' => 'CPL-KK',
            'Pengetahuan'         => 'CPL-P',
            default               => 'CPL-X',
        };

        $lastUrutan = CplSndikti::where('kategori', $validated['kategori'])->max('urutan') ?? 0;
        $nextUrutan = $lastUrutan + 1;

        CplSndikti::create([
            'kode'      => $prefix . str_pad($nextUrutan, 2, '0', STR_PAD_LEFT),
            'deskripsi' => $validated['deskripsi'],
            'kategori'  => $validated['kategori'],
            'urutan'    => $nextUrutan,
        ]);

        return redirect()
            ->route('kurikulum.cpl-sndikti.index', $kurikulum)
            ->with('success', 'CPL SN-Dikti berhasil ditambahkan.');
    }

    public function edit(Kurikulum $kurikulum, CplSndikti $cplSndikti)
    {
        $kategoriOptions = ['Sikap', 'Keterampilan Umum', 'Keterampilan Khusus', 'Pengetahuan'];
        return view('kurikulum.cpl-sndikti.edit', compact('kurikulum', 'cplSndikti', 'kategoriOptions'));
    }

    public function update(Request $request, Kurikulum $kurikulum, CplSndikti $cplSndikti)
    {
        $validated = $request->validate([
            'kategori'  => 'required|in:Sikap,Keterampilan Umum,Keterampilan Khusus,Pengetahuan',
            'deskripsi' => 'required|string',
        ]);
        $cplSndikti->update($validated);

        return redirect()
            ->route('kurikulum.cpl-sndikti.index', $kurikulum)
            ->with('success', 'CPL SN-Dikti berhasil diperbarui.');
    }

    public function destroy(Kurikulum $kurikulum, CplSndikti $cplSndikti)
    {
        $cplSndikti->delete();

        return redirect()
            ->route('kurikulum.cpl-sndikti.index', $kurikulum)
            ->with('success', 'CPL SN-Dikti berhasil dihapus.');
    }
}
