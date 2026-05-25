<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function index()
    {
        // TODO: halaman pilihan laporan
        abort(501, 'Not implemented');
    }

    public function cpl(string $kurikulum)
    {
        // TODO: laporan ketercapaian CPL per kurikulum (hasil_cpl)
        abort(501, 'Not implemented');
    }

    public function pl(string $kurikulum)
    {
        // TODO: laporan ketercapaian PL per kurikulum (hasil_pl)
        abort(501, 'Not implemented');
    }

    public function ketercapaian()
    {
        // TODO: laporan lintas kurikulum / semester
        abort(501, 'Not implemented');
    }
}
