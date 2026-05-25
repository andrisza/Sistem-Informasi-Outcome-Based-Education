<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubCpmkController extends Controller
{
    public function index(string $kurikulum, string $mataKuliah, string $cpmk) { abort(501, 'Not implemented'); }
    public function create(string $kurikulum, string $mataKuliah, string $cpmk) { abort(501, 'Not implemented'); }
    public function store(Request $request, string $kurikulum, string $mataKuliah, string $cpmk) { abort(501, 'Not implemented'); }
    public function show(string $kurikulum, string $mataKuliah, string $cpmk, string $subCpmk) { abort(501, 'Not implemented'); }
    public function edit(string $kurikulum, string $mataKuliah, string $cpmk, string $subCpmk) { abort(501, 'Not implemented'); }
    public function update(Request $request, string $kurikulum, string $mataKuliah, string $cpmk, string $subCpmk) { abort(501, 'Not implemented'); }
    public function destroy(string $kurikulum, string $mataKuliah, string $cpmk, string $subCpmk) { abort(501, 'Not implemented'); }
}
