<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\KomentarReview;
use App\Services\NotifikasiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KomentarReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'model_type' => 'required|string|max:150',
            'model_id'   => 'required|integer',
            'konten'     => 'required|string',
            'elemen'     => 'nullable|string|max:200',
        ]);

        $validated['id_user'] = Auth::id();
        $validated['status']  = 'open';

        KomentarReview::create($validated);

        // Notifikasi ke pemilik dokumen jika model mendukung
        try {
            $modelClass = $validated['model_type'];
            if (class_exists($modelClass)) {
                $model = $modelClass::find($validated['model_id']);
                if ($model && isset($model->id_dosen_pengembang) && $model->id_dosen_pengembang !== Auth::id()) {
                    NotifikasiService::kirim(
                        $model->id_dosen_pengembang,
                        'Komentar Baru pada Dokumen Anda',
                        Auth::user()->name . ' memberikan komentar: ' . \Str::limit($validated['konten'], 80),
                        null,
                        'review'
                    );
                }
            }
        } catch (\Throwable) {
            // abaikan jika model tidak mendukung
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }

    public function resolve(KomentarReview $komentar)
    {
        $komentar->update(['status' => 'resolved']);

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Komentar ditandai sebagai resolved.');
    }

    public function destroy(KomentarReview $komentar)
    {
        $komentar->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Komentar berhasil dihapus.');
    }
}
