<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CqiController extends Controller
{
    public function index()
    {
        // TODO: list log_evaluasi_cqi dengan filter status
        abort(501, 'Not implemented');
    }

    public function show(string $log)
    {
        // TODO: detail evaluasi CQI
        abort(501, 'Not implemented');
    }

    public function setujui(Request $request, string $log)
    {
        // TODO: set disetujui_oleh = auth user, ubah status
        abort(501, 'Not implemented');
    }

    public function update(Request $request, string $log)
    {
        // TODO: update status (belum → proses → selesai)
        abort(501, 'Not implemented');
    }
}
