<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    /** Redirect ke dashboard sesuai peran pengguna yang login */
    public function redirect(): RedirectResponse
    {
        return redirect()->route(auth()->user()->dashboardRoute());
    }
}
