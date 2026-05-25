<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function index()
    {
        // TODO: pilih MK yang diampu untuk input nilai
        abort(501, 'Not implemented');
    }

    public function form(string $mk, string $semester)
    {
        // TODO: form input nilai_mahasiswa per komponen_asesmen
        // Validasi: dosen hanya bisa akses MK dalam pengampuan_mk miliknya
        abort(501, 'Not implemented');
    }

    public function store(Request $request, string $mk, string $semester)
    {
        // TODO: simpan batch nilai, trigger RecalculateObeJob
        abort(501, 'Not implemented');
    }

    public function rekap(string $mk, string $semester)
    {
        // TODO: tampilkan rekap nilai + status Sub-CPMK/CPMK/CPL per mahasiswa
        abort(501, 'Not implemented');
    }
}
