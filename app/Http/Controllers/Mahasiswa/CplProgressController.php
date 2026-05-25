<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;

class CplProgressController extends Controller
{
    public function index()
    {
        // TODO: tampilkan semua CPL dengan progress ketercapaian (dari hasil_cpl)
        abort(501, 'Not implemented');
    }

    public function show(string $cpl)
    {
        // TODO: detail satu CPL — CPMK & Sub-CPMK yang berkontribusi, nilai per MK
        abort(501, 'Not implemented');
    }

    public function plProgress()
    {
        // TODO: tampilkan progress ketercapaian PL (dari hasil_pl)
        abort(501, 'Not implemented');
    }
}
