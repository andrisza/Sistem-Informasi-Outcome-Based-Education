<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;

class MateriController extends Controller
{
    public function index()
    {
        // TODO: list materi untuk MK yang diikuti mahasiswa semester ini
        abort(501, 'Not implemented');
    }

    public function show(string $materi)
    {
        // TODO: preview / download file materi
        // Validasi: mahasiswa hanya bisa akses materi MK yang dia ikuti (enrollment_mk)
        abort(501, 'Not implemented');
    }
}
