<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\Kurikulum;
use App\Models\PeriodeKurikulum;
use App\Models\TimKurikulum;
use App\Models\User;
use Illuminate\Http\Request;

class TimKurikulumController extends Controller
{
    public function index(Kurikulum $kurikulum)
    {
        $periodeList = $kurikulum->periode()->with(['timKurikulum.user'])->orderBy('tanggal_mulai', 'desc')->get();

        return view('kurikulum.tim.index', compact('kurikulum', 'periodeList'));
    }

    public function create(Kurikulum $kurikulum)
    {
        $periodeList = $kurikulum->periode()->orderBy('nama_periode')->get();
        $userList    = User::whereIn('role', ['dosen', 'kaprodi', 'tim_kurikulum'])->orderBy('name')->get();

        return view('kurikulum.tim.create', compact('kurikulum', 'periodeList', 'userList'));
    }

    public function store(Request $request, Kurikulum $kurikulum)
    {
        $validated = $request->validate([
            'id_periode'  => 'required|exists:periode_kurikulum,id',
            'id_user'     => 'required|exists:users,id',
            'jabatan_tim' => 'required|string|max:255',
            'sk_nomor'    => 'nullable|string|max:255',
        ]);

        // Pastikan periode milik kurikulum ini
        $periode = PeriodeKurikulum::where('id', $validated['id_periode'])
            ->where('id_kurikulum', $kurikulum->id)
            ->firstOrFail();

        // Cek duplikasi
        $exists = TimKurikulum::where('id_periode', $periode->id)
            ->where('id_user', $validated['id_user'])
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Anggota tersebut sudah terdaftar di periode ini.');
        }

        TimKurikulum::create(array_merge($validated, ['created_at' => now()]));

        return redirect()
            ->route('kurikulum.tim.index', $kurikulum)
            ->with('success', 'Anggota tim berhasil ditambahkan.');
    }

    public function show(Kurikulum $kurikulum, TimKurikulum $tim)
    {
        $tim->load(['user', 'periode']);
        return view('kurikulum.tim.show', compact('kurikulum', 'tim'));
    }

    public function edit(Kurikulum $kurikulum, TimKurikulum $tim)
    {
        $periodeList = $kurikulum->periode()->orderBy('nama_periode')->get();
        $userList    = User::whereIn('role', ['dosen', 'kaprodi', 'tim_kurikulum'])->orderBy('name')->get();

        return view('kurikulum.tim.edit', compact('kurikulum', 'tim', 'periodeList', 'userList'));
    }

    public function update(Request $request, Kurikulum $kurikulum, TimKurikulum $tim)
    {
        $validated = $request->validate([
            'id_periode'  => 'required|exists:periode_kurikulum,id',
            'id_user'     => 'required|exists:users,id',
            'jabatan_tim' => 'required|string|max:255',
            'sk_nomor'    => 'nullable|string|max:255',
        ]);

        $tim->update($validated);

        return redirect()
            ->route('kurikulum.tim.index', $kurikulum)
            ->with('success', 'Data anggota tim berhasil diperbarui.');
    }

    public function destroy(Kurikulum $kurikulum, TimKurikulum $tim)
    {
        $tim->delete();

        return redirect()
            ->route('kurikulum.tim.index', $kurikulum)
            ->with('success', 'Anggota tim berhasil dihapus.');
    }
}
