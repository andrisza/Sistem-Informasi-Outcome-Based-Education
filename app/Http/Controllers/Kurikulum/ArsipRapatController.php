<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\ArsipRapat;
use App\Models\Kurikulum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArsipRapatController extends Controller
{
    public function index(Request $request, Kurikulum $kurikulum)
    {
        $query = $kurikulum->arsipRapat()
            ->with(['periode', 'pembuat'])
            ->orderBy('tanggal_rapat', 'desc');

        if ($request->filled('jenis')) {
            $query->where('jenis_rapat', $request->jenis);
        }

        $arsipList = $query->paginate(15)->withQueryString();

        // Hitung per jenis untuk summary stats (tanpa filter aktif)
        $jenisCounts = $kurikulum->arsipRapat()
            ->selectRaw('jenis_rapat, count(*) as total')
            ->groupBy('jenis_rapat')
            ->pluck('total', 'jenis_rapat');

        return view('kurikulum.arsip-rapat.index', compact('kurikulum', 'arsipList', 'jenisCounts'));
    }

    public function create(Kurikulum $kurikulum)
    {
        $periodeList = $kurikulum->periode()->orderBy('nama_periode')->get();
        $jenisOptions = [
            'pleno_kurikulum'   => 'Pleno Kurikulum',
            'rapat_tim_kecil'   => 'Rapat Tim Kecil',
            'rapat_stakeholder' => 'Rapat Stakeholder',
            'rapat_evaluasi'    => 'Rapat Evaluasi',
            'rapat_cqi'         => 'Rapat CQI',
            'rapat_lainnya'     => 'Rapat Lainnya',
        ];

        return view('kurikulum.arsip-rapat.create', compact('kurikulum', 'periodeList', 'jenisOptions'));
    }

    public function store(Request $request, Kurikulum $kurikulum)
    {
        $validated = $request->validate([
            'id_periode'    => 'nullable|exists:periode_kurikulum,id',
            'judul_rapat'   => 'required|string|max:255',
            'jenis_rapat'   => 'required|in:pleno_kurikulum,rapat_tim_kecil,rapat_stakeholder,rapat_evaluasi,rapat_cqi,rapat_lainnya',
            'tanggal_rapat' => 'required|date',
            'tempat'        => 'nullable|string|max:255',
            'agenda'        => 'nullable|string',
            'notulen'       => 'nullable|string',
            'kesimpulan'    => 'nullable|string',
            'tindak_lanjut' => 'nullable|string',
        ]);

        $validated['id_kurikulum'] = $kurikulum->id;
        $validated['dibuat_oleh']  = Auth::id();

        ArsipRapat::create($validated);

        return redirect()
            ->route('kurikulum.arsip-rapat.index', $kurikulum)
            ->with('success', 'Arsip rapat berhasil ditambahkan.');
    }

    public function show(Kurikulum $kurikulum, ArsipRapat $arsipRapat)
    {
        $arsipRapat->load(['periode', 'pembuat']);
        return view('kurikulum.arsip-rapat.show', compact('kurikulum', 'arsipRapat'));
    }

    public function edit(Kurikulum $kurikulum, ArsipRapat $arsipRapat)
    {
        $periodeList = $kurikulum->periode()->orderBy('nama_periode')->get();
        $jenisOptions = [
            'pleno_kurikulum'   => 'Pleno Kurikulum',
            'rapat_tim_kecil'   => 'Rapat Tim Kecil',
            'rapat_stakeholder' => 'Rapat Stakeholder',
            'rapat_evaluasi'    => 'Rapat Evaluasi',
            'rapat_cqi'         => 'Rapat CQI',
            'rapat_lainnya'     => 'Rapat Lainnya',
        ];

        return view('kurikulum.arsip-rapat.edit', compact('kurikulum', 'arsipRapat', 'periodeList', 'jenisOptions'));
    }

    public function update(Request $request, Kurikulum $kurikulum, ArsipRapat $arsipRapat)
    {
        $validated = $request->validate([
            'id_periode'    => 'nullable|exists:periode_kurikulum,id',
            'judul_rapat'   => 'required|string|max:255',
            'jenis_rapat'   => 'required|in:pleno_kurikulum,rapat_tim_kecil,rapat_stakeholder,rapat_evaluasi,rapat_cqi,rapat_lainnya',
            'tanggal_rapat' => 'required|date',
            'tempat'        => 'nullable|string|max:255',
            'agenda'        => 'nullable|string',
            'notulen'       => 'nullable|string',
            'kesimpulan'    => 'nullable|string',
            'tindak_lanjut' => 'nullable|string',
        ]);

        $arsipRapat->update($validated);

        return redirect()
            ->route('kurikulum.arsip-rapat.index', $kurikulum)
            ->with('success', 'Arsip rapat berhasil diperbarui.');
    }

    public function destroy(Kurikulum $kurikulum, ArsipRapat $arsipRapat)
    {
        $arsipRapat->delete();

        return redirect()
            ->route('kurikulum.arsip-rapat.index', $kurikulum)
            ->with('success', 'Arsip rapat berhasil dihapus.');
    }
}
