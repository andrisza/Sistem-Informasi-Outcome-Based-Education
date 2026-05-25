<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SemesterAkademikController extends Controller
{
    public function index()
    {
        // TODO: list semester akademik
        abort(501, 'Not implemented');
    }

    public function create()
    {
        abort(501, 'Not implemented');
    }

    public function store(Request $request)
    {
        // TODO: buat semester baru, pastikan tidak lebih dari 1 aktif
        abort(501, 'Not implemented');
    }

    public function edit(string $semesterAkademik)
    {
        abort(501, 'Not implemented');
    }

    public function update(Request $request, string $semesterAkademik)
    {
        abort(501, 'Not implemented');
    }

    public function destroy(string $semesterAkademik)
    {
        abort(501, 'Not implemented');
    }

    public function aktifkan(string $semesterAkademik)
    {
        // TODO: set is_aktif=1, set yang lain is_aktif=0
        abort(501, 'Not implemented');
    }
}
