<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// ── Halaman awal → redirect ke login ─────────────────────────────────────────
Route::get('/', fn () => redirect()->route('login'));

// ── Autentikasi (login / logout) ──────────────────────────────────────────────
require __DIR__.'/auth.php';

// ── Smart redirect: setelah login → dashboard sesuai peran ───────────────────
Route::middleware('auth')
    ->get('/dashboard', [DashboardController::class, 'redirect'])
    ->name('dashboard');

// ── Kaprodi (super admin) ─────────────────────────────────────────────────────
// Akses: kaprodi saja
Route::middleware(['auth', 'role:kaprodi'])
    ->prefix('kaprodi')
    ->name('kaprodi.')
    ->group(base_path('routes/kaprodi.php'));

// ── Manajemen Kurikulum ───────────────────────────────────────────────────────
// Akses: kaprodi + tim_kurikulum
// Lock/arsip kurikulum hanya kaprodi (dijaga di controller dengan $this->authorize)
Route::middleware(['auth', 'role:kaprodi,tim_kurikulum'])
    ->prefix('kurikulum')
    ->name('kurikulum.')
    ->group(base_path('routes/kurikulum.php'));

// ── Dosen (kontributor) ───────────────────────────────────────────────────────
// Akses: dosen saja
Route::middleware(['auth', 'role:dosen'])
    ->prefix('dosen')
    ->name('dosen.')
    ->group(base_path('routes/dosen.php'));

// ── Mahasiswa (viewer) ────────────────────────────────────────────────────────
// Akses: mahasiswa saja
Route::middleware(['auth', 'role:mahasiswa'])
    ->prefix('mahasiswa')
    ->name('mahasiswa.')
    ->group(base_path('routes/mahasiswa.php'));
