<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasi = Notifikasi::where('id_user', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('notifikasi.index', compact('notifikasi'));
    }

    public function markRead(Notifikasi $notif)
    {
        // pastikan notif milik user ini
        abort_unless($notif->id_user === Auth::id(), 403);

        $notif->update(['dibaca' => 1]);

        if ($notif->url) {
            return redirect($notif->url);
        }

        return back()->with('success', 'Notifikasi ditandai sebagai dibaca.');
    }

    public function markAllRead()
    {
        Notifikasi::where('id_user', Auth::id())
            ->where('dibaca', 0)
            ->update(['dibaca' => 1]);

        return back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }
}
