<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JurnalMengajarController extends Controller
{
    public function index()
    {
        // TODO: list jurnal_mengajar milik dosen yang login
        abort(501, 'Not implemented');
    }

    public function create()
    {
        // TODO: form tambah jurnal (pilih rps_pertemuan dari pengampuan aktif)
        abort(501, 'Not implemented');
    }

    public function store(Request $request)
    {
        // TODO: simpan jurnal + upload file bukti
        abort(501, 'Not implemented');
    }

    public function show(string $jurnal)
    {
        abort(501, 'Not implemented');
    }

    public function edit(string $jurnal)
    {
        abort(501, 'Not implemented');
    }

    public function update(Request $request, string $jurnal)
    {
        abort(501, 'Not implemented');
    }
}
