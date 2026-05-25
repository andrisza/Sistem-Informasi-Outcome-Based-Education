<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MateriController extends Controller
{
    public function index()
    {
        // TODO: list repositori_materi milik dosen yang login
        abort(501, 'Not implemented');
    }

    public function create()
    {
        // TODO: form upload materi (pilih MK dari pengampuan)
        abort(501, 'Not implemented');
    }

    public function store(Request $request)
    {
        // TODO: upload & simpan ke repositori_materi
        abort(501, 'Not implemented');
    }

    public function destroy(string $materi)
    {
        // TODO: soft delete, pastikan materi milik dosen yang login
        abort(501, 'Not implemented');
    }
}
