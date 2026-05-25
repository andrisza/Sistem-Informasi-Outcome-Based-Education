<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RpsApprovalController extends Controller
{
    public function index()
    {
        // TODO: list RPS berstatus 'review' yang menunggu persetujuan
        abort(501, 'Not implemented');
    }

    public function show(string $rps)
    {
        // TODO: tampilkan detail RPS untuk direview
        abort(501, 'Not implemented');
    }

    public function approve(Request $request, string $rps)
    {
        // TODO: ubah status RPS → 'disahkan', set disahkan_pada & id_kaprodi_pengesah
        abort(501, 'Not implemented');
    }

    public function reject(Request $request, string $rps)
    {
        // TODO: kembalikan status RPS → 'draft' dengan catatan penolakan
        abort(501, 'Not implemented');
    }
}
