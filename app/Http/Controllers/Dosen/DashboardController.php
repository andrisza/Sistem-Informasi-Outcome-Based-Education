<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // TODO: tampilkan MK yang diampu semester aktif, status RPS, jurnal pending
        abort(501, 'Not implemented');
    }

    public function pengampuan()
    {
        // TODO: list pengampuan_mk milik dosen yang login
        abort(501, 'Not implemented');
    }
}
