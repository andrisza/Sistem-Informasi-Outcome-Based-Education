<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // TODO: list semua user dengan filter peran
        abort(501, 'Not implemented');
    }

    public function create()
    {
        // TODO: form buat user baru
        abort(501, 'Not implemented');
    }

    public function store(Request $request)
    {
        // TODO: validasi & simpan user baru
        abort(501, 'Not implemented');
    }

    public function show(string $user)
    {
        // TODO: detail profil user
        abort(501, 'Not implemented');
    }

    public function edit(string $user)
    {
        // TODO: form edit user
        abort(501, 'Not implemented');
    }

    public function update(Request $request, string $user)
    {
        // TODO: validasi & update user
        abort(501, 'Not implemented');
    }

    public function destroy(string $user)
    {
        // TODO: soft delete user
        abort(501, 'Not implemented');
    }

    public function toggleStatus(string $user)
    {
        // TODO: toggle aktif/nonaktif
        abort(501, 'Not implemented');
    }
}
