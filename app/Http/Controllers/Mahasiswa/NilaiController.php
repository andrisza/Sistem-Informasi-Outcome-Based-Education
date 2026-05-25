<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;

class NilaiController extends Controller
{
    public function index()
    {
        // TODO: rekap nilai semua MK yang pernah diikuti
        abort(501, 'Not implemented');
    }

    public function show(string $mk, string $semester)
    {
        // TODO: detail nilai per komponen_asesmen untuk MK tertentu
        // Validasi: mahasiswa hanya bisa lihat nilai miliknya sendiri
        abort(501, 'Not implemented');
    }
}
