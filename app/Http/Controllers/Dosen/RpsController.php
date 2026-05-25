<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RpsController extends Controller
{
    public function index()
    {
        // TODO: list rps_header milik dosen yang login (scope: id_dosen_pengembang)
        abort(501, 'Not implemented');
    }

    public function create()
    {
        // TODO: form buat RPS baru (pilih MK dari pengampuan semester aktif)
        abort(501, 'Not implemented');
    }

    public function store(Request $request)
    {
        // TODO: simpan rps_header, status = 'draft'
        abort(501, 'Not implemented');
    }

    public function show(string $rps)
    {
        abort(501, 'Not implemented');
    }

    public function edit(string $rps)
    {
        // TODO: hanya bisa edit jika status = 'draft' atau 'review'
        abort(501, 'Not implemented');
    }

    public function update(Request $request, string $rps)
    {
        abort(501, 'Not implemented');
    }

    public function destroy(string $rps)
    {
        // TODO: hanya bisa hapus jika status = 'draft'
        abort(501, 'Not implemented');
    }

    public function submit(string $rps)
    {
        // TODO: ubah status → 'review', kirim notifikasi ke kaprodi
        abort(501, 'Not implemented');
    }

    public function pertemuanIndex(string $rps)
    {
        // TODO: list 16 pertemuan RPS
        abort(501, 'Not implemented');
    }

    public function pertemuanShow(string $rps, string $minggu)
    {
        abort(501, 'Not implemented');
    }

    public function pertemuanUpdate(Request $request, string $rps, string $minggu)
    {
        abort(501, 'Not implemented');
    }
}
