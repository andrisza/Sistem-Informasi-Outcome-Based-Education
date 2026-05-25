<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PeriodeKurikulumController extends Controller
{
    public function index(string $kurikulum) { abort(501, 'Not implemented'); }
    public function create(string $kurikulum) { abort(501, 'Not implemented'); }
    public function store(Request $request, string $kurikulum) { abort(501, 'Not implemented'); }
    public function show(string $kurikulum, string $periode) { abort(501, 'Not implemented'); }
    public function edit(string $kurikulum, string $periode) { abort(501, 'Not implemented'); }
    public function update(Request $request, string $kurikulum, string $periode) { abort(501, 'Not implemented'); }
    public function destroy(string $kurikulum, string $periode) { abort(501, 'Not implemented'); }
}
