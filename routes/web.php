<?php

use App\Http\Controllers\NotifikasiController;
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

// ── Master Data (kaprodi + tim_kurikulum) ─────────────────────────────────────
// Akses: kaprodi + tim_kurikulum — route bersama yang tidak perlu prefix role
Route::middleware(['auth', 'role:kaprodi,tim_kurikulum'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/master-kategori',                            [\App\Http\Controllers\Kaprodi\MasterKategoriController::class, 'index'])->name('master-kategori.index');
        Route::post('/master-kategori',                           [\App\Http\Controllers\Kaprodi\MasterKategoriController::class, 'store'])->name('master-kategori.store');
        Route::put('/master-kategori/{masterKategori}',           [\App\Http\Controllers\Kaprodi\MasterKategoriController::class, 'update'])->name('master-kategori.update');
        Route::post('/master-kategori/{masterKategori}/toggle',   [\App\Http\Controllers\Kaprodi\MasterKategoriController::class, 'toggleAktif'])->name('master-kategori.toggle');
    });

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

// ── Notifikasi (semua user yang terautentikasi) ───────────────────────────────
Route::middleware('auth')->prefix('notifikasi')->name('notifikasi.')->group(function () {
    Route::get('/', [NotifikasiController::class, 'index'])->name('index');
    Route::post('/{notif}/read', [NotifikasiController::class, 'markRead'])->name('read');
    Route::post('/read-all', [NotifikasiController::class, 'markAllRead'])->name('read-all');
});
