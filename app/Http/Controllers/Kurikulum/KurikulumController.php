<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KurikulumController extends Controller
{
    public function index()
    {
        // TODO: list semua kurikulum dengan status
        abort(501, 'Not implemented');
    }

    public function create()
    {
        abort(501, 'Not implemented');
    }

    public function store(Request $request)
    {
        // TODO: buat kurikulum baru, set dibuat_oleh = auth user
        abort(501, 'Not implemented');
    }

    public function show(string $kurikulum)
    {
        // TODO: detail kurikulum (overview semua elemen)
        abort(501, 'Not implemented');
    }

    public function edit(string $kurikulum)
    {
        abort(501, 'Not implemented');
    }

    public function update(Request $request, string $kurikulum)
    {
        abort(501, 'Not implemented');
    }

    public function destroy(string $kurikulum)
    {
        // TODO: hanya boleh jika status masih 'draft'
        abort(501, 'Not implemented');
    }

    public function arsip(string $kurikulum)
    {
        // TODO: ubah status → 'arsip', set locked_at & locked_by (kaprodi only via Gate)
        abort(501, 'Not implemented');
    }

    public function aktifkan(string $kurikulum)
    {
        // TODO: ubah status → 'aktif' (kaprodi only via Gate)
        abort(501, 'Not implemented');
    }
}
