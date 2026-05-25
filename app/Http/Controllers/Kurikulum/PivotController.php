<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PivotController extends Controller
{
    // Matriks PL ↔ CPL Prodi
    public function plCpl(string $kurikulum) { abort(501, 'Not implemented'); }
    public function savePlCpl(Request $request, string $kurikulum) { abort(501, 'Not implemented'); }

    // Matriks CPL SN-Dikti ↔ CPL Prodi
    public function cplsnCplp(string $kurikulum) { abort(501, 'Not implemented'); }
    public function saveCplsnCplp(Request $request, string $kurikulum) { abort(501, 'Not implemented'); }

    // Matriks CPL Prodi ↔ Bahan Kajian
    public function cplBk(string $kurikulum) { abort(501, 'Not implemented'); }
    public function saveCplBk(Request $request, string $kurikulum) { abort(501, 'Not implemented'); }

    // Matriks MK ↔ Bahan Kajian
    public function mkBk(string $kurikulum) { abort(501, 'Not implemented'); }
    public function saveMkBk(Request $request, string $kurikulum) { abort(501, 'Not implemented'); }

    // Matriks MK ↔ CPL (via CPMK)
    public function mkCpl(string $kurikulum) { abort(501, 'Not implemented'); }
    public function saveMkCpl(Request $request, string $kurikulum) { abort(501, 'Not implemented'); }

    // Matriks 3 dimensi CPL ↔ BK ↔ MK
    public function cplBkMk(string $kurikulum) { abort(501, 'Not implemented'); }
    public function saveCplBkMk(Request $request, string $kurikulum) { abort(501, 'Not implemented'); }
}
