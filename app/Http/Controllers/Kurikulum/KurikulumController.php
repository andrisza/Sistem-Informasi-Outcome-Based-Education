<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\Kurikulum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KurikulumController extends Controller
{
    public function index()
    {
        $status    = request('status');
        $search    = request('search');

        $kurikulumList = Kurikulum::query()
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($search, fn ($q) => $q->where(function ($q2) use ($search) {
                $q2->where('nama_kurikulum', 'like', "%{$search}%")
                   ->orWhere('kode', 'like', "%{$search}%")
                   ->orWhere('program_studi', 'like', "%{$search}%");
            }))
            ->withCount(['mataKuliah', 'cplProdi', 'pl'])
            ->orderBy('tahun_mulai', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('kurikulum.index', compact('kurikulumList', 'status', 'search'));
    }

    public function create()
    {
        return view('kurikulum.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode'          => 'required|string|max:50|unique:kurikulum,kode',
            'nama_kurikulum'=> 'required|string|max:255',
            'program_studi' => 'required|string|max:255',
            'jenjang'       => 'required|in:D3,D4,S1,S2,S3',
            'tahun_mulai'   => 'required|integer|min:2000|max:2100',
            'tahun_selesai' => 'nullable|integer|min:2000|max:2100|gte:tahun_mulai',
            'visi'          => 'nullable|string',
            'misi'          => 'nullable|string',
            'tujuan'        => 'nullable|string',
            'sasaran'       => 'nullable|string',
        ]);

        $validated['status']     = 'draft';
        $validated['dibuat_oleh'] = Auth::id();

        $kurikulum = Kurikulum::create($validated);

        return redirect()
            ->route('kurikulum.show', $kurikulum)
            ->with('success', 'Kurikulum berhasil dibuat.');
    }

    public function show(Kurikulum $kurikulum)
    {
        $kurikulum->loadCount(['pl', 'cplProdi', 'bahanKajian', 'mataKuliah', 'periode', 'arsipRapat']);

        return view('kurikulum.show', compact('kurikulum'));
    }

    public function edit(Kurikulum $kurikulum)
    {
        return view('kurikulum.edit', compact('kurikulum'));
    }

    public function update(Request $request, Kurikulum $kurikulum)
    {
        $validated = $request->validate([
            'kode'          => 'required|string|max:50|unique:kurikulum,kode,' . $kurikulum->id,
            'nama_kurikulum'=> 'required|string|max:255',
            'program_studi' => 'required|string|max:255',
            'jenjang'       => 'required|in:D3,D4,S1,S2,S3',
            'tahun_mulai'   => 'required|integer|min:2000|max:2100',
            'tahun_selesai' => 'nullable|integer|min:2000|max:2100|gte:tahun_mulai',
            'visi'          => 'nullable|string',
            'misi'          => 'nullable|string',
            'tujuan'        => 'nullable|string',
            'sasaran'       => 'nullable|string',
        ]);

        $kurikulum->update($validated);

        return redirect()
            ->route('kurikulum.show', $kurikulum)
            ->with('success', 'Kurikulum berhasil diperbarui.');
    }

    public function destroy(Kurikulum $kurikulum)
    {
        if ($kurikulum->status !== 'draft') {
            return back()->with('error', 'Hanya kurikulum berstatus Draft yang dapat dihapus.');
        }

        $kurikulum->delete();

        return redirect()
            ->route('kurikulum.index')
            ->with('success', 'Kurikulum berhasil dihapus.');
    }

    public function arsip(Kurikulum $kurikulum)
    {
        // Hanya kaprodi
        if (!Auth::user()->isKaprodi()) {
            abort(403);
        }

        $kurikulum->update([
            'status'     => 'arsip',
            'locked_at'  => now(),
            'locked_by'  => Auth::id(),
        ]);

        return back()->with('success', 'Kurikulum berhasil diarsipkan.');
    }

    public function aktifkan(Kurikulum $kurikulum)
    {
        // Hanya kaprodi
        if (!Auth::user()->isKaprodi()) {
            abort(403);
        }

        $kurikulum->update([
            'status'     => 'aktif',
            'locked_at'  => null,
            'locked_by'  => null,
        ]);

        return back()->with('success', 'Kurikulum berhasil diaktifkan.');
    }
}
