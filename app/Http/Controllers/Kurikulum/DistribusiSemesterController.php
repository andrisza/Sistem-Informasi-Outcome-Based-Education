<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;

class DistribusiSemesterController extends Controller
{
    public function index(string $kurikulum)
    {
        // TODO: tampilkan tabel distribusi SKS per semester (read-only, update via MK observer)
        abort(501, 'Not implemented');
    }
}
