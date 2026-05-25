<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // TODO: ringkasan — MK aktif, progress CPL, nilai terkini
        abort(501, 'Not implemented');
    }

    public function rpsShow(string $mk, string $semester)
    {
        // TODO: tampilkan RPS (read-only) untuk MK yang diikuti mahasiswa
        abort(501, 'Not implemented');
    }
}
