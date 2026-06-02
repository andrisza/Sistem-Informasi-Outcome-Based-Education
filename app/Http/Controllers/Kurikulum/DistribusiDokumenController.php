<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\Kurikulum;
use App\Models\LogDistribusi;
use App\Models\User;
use App\Services\NotifikasiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class DistribusiDokumenController extends Controller
{
    public function index(Kurikulum $kurikulum)
    {
        Gate::authorize('modify-kurikulum', $kurikulum);

        $logs = LogDistribusi::with(['pengirim', 'penerima'])
            ->where('id_kurikulum', $kurikulum->id)
            ->orderByDesc('created_at')
            ->get();

        $users = User::whereIn('role', ['tim_kurikulum', 'dosen', 'kaprodi'])
            ->orderBy('name')
            ->get();

        return view('kurikulum.distribusi.index', compact('kurikulum', 'logs', 'users'));
    }

    public function store(Request $request, Kurikulum $kurikulum)
    {
        Gate::authorize('modify-kurikulum', $kurikulum);

        $validated = $request->validate([
            'nama_dokumen' => 'required|string|max:255',
            'versi'        => 'nullable|string|max:50',
            'catatan'      => 'nullable|string',
            'id_penerima'  => 'required|array|min:1',
            'id_penerima.*'=> 'exists:users,id',
        ]);

        foreach ($validated['id_penerima'] as $penerimaId) {
            LogDistribusi::create([
                'id_kurikulum' => $kurikulum->id,
                'id_pengirim'  => Auth::id(),
                'id_penerima'  => $penerimaId,
                'nama_dokumen' => $validated['nama_dokumen'],
                'versi'        => $validated['versi'] ?? null,
                'catatan'      => $validated['catatan'] ?? null,
            ]);
        }

        // Kirim notifikasi ke penerima
        NotifikasiService::kirim(
            $validated['id_penerima'],
            'Dokumen Kurikulum Dibagikan',
            "Dokumen \"{$validated['nama_dokumen']}\" kurikulum {$kurikulum->kode} telah dibagikan kepada Anda.",
            route('kurikulum.show', $kurikulum),
            'info'
        );

        return redirect()
            ->route('kurikulum.distribusi.index', $kurikulum)
            ->with('success', 'Dokumen berhasil didistribusikan.');
    }
}
