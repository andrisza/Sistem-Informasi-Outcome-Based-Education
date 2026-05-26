<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\Kurikulum;
use App\Models\PeriodeKurikulum;
use App\Models\User;
use Illuminate\Http\Request;

class PeriodeKurikulumController extends Controller
{
    public function index(Kurikulum $kurikulum)
    {
        $periodeList = $kurikulum->periode()
            ->with('ketuaTim')
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        return view('kurikulum.periode.index', compact('kurikulum', 'periodeList'));
    }

    public function create(Kurikulum $kurikulum)
    {
        $dosenList = User::where('role', 'dosen')->orderBy('name')->get();
        return view('kurikulum.periode.create', compact('kurikulum', 'dosenList'));
    }

    public function store(Request $request, Kurikulum $kurikulum)
    {
        $validated = $request->validate([
            'nama_periode'   => 'required|string|max:255',
            'tanggal_mulai'  => 'required|date',
            'tanggal_selesai'=> 'nullable|date|after_or_equal:tanggal_mulai',
            'ketua_tim'      => 'nullable|exists:users,id',
            'deskripsi'      => 'nullable|string',
            'dokumen_sk'     => 'nullable|string|max:255',
            'status'         => 'required|in:perencanaan,berjalan,selesai',
        ]);

        $validated['id_kurikulum'] = $kurikulum->id;
        $kurikulum->periode()->create($validated);

        return redirect()
            ->route('kurikulum.periode.index', $kurikulum)
            ->with('success', 'Periode berhasil ditambahkan.');
    }

    public function show(Kurikulum $kurikulum, PeriodeKurikulum $periode)
    {
        $periode->load(['ketuaTim', 'timKurikulum.user']);
        return view('kurikulum.periode.show', compact('kurikulum', 'periode'));
    }

    public function edit(Kurikulum $kurikulum, PeriodeKurikulum $periode)
    {
        $dosenList = User::where('role', 'dosen')->orderBy('name')->get();
        return view('kurikulum.periode.edit', compact('kurikulum', 'periode', 'dosenList'));
    }

    public function update(Request $request, Kurikulum $kurikulum, PeriodeKurikulum $periode)
    {
        $validated = $request->validate([
            'nama_periode'   => 'required|string|max:255',
            'tanggal_mulai'  => 'required|date',
            'tanggal_selesai'=> 'nullable|date|after_or_equal:tanggal_mulai',
            'ketua_tim'      => 'nullable|exists:users,id',
            'deskripsi'      => 'nullable|string',
            'dokumen_sk'     => 'nullable|string|max:255',
            'status'         => 'required|in:perencanaan,berjalan,selesai',
        ]);

        $periode->update($validated);

        return redirect()
            ->route('kurikulum.periode.index', $kurikulum)
            ->with('success', 'Periode berhasil diperbarui.');
    }

    public function destroy(Kurikulum $kurikulum, PeriodeKurikulum $periode)
    {
        $periode->delete();

        return redirect()
            ->route('kurikulum.periode.index', $kurikulum)
            ->with('success', 'Periode berhasil dihapus.');
    }
}
