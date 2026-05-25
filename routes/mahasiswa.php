<?php

use App\Http\Controllers\Mahasiswa\CplProgressController;
use App\Http\Controllers\Mahasiswa\DashboardController;
use App\Http\Controllers\Mahasiswa\MateriController;
use App\Http\Controllers\Mahasiswa\NilaiController;
use Illuminate\Support\Facades\Route;

// ── Dashboard ─────────────────────────────────────────────────────────────────
// GET /mahasiswa/dashboard  →  mahasiswa.dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// ── Nilai & Transkrip ─────────────────────────────────────────────────────────
// GET /mahasiswa/nilai                  →  mahasiswa.nilai.index  (rekap semua MK)
// GET /mahasiswa/nilai/{mk}/{semester}  →  mahasiswa.nilai.show   (detail per MK)
Route::prefix('nilai')->name('nilai.')->group(function () {
    Route::get('/', [NilaiController::class, 'index'])->name('index');
    Route::get('/{mk}/{semester}', [NilaiController::class, 'show'])->name('show');
});

// ── Progress Capaian CPL & PL ─────────────────────────────────────────────────
// GET /mahasiswa/cpl-progress        →  mahasiswa.cpl-progress.index
// GET /mahasiswa/cpl-progress/{cpl}  →  mahasiswa.cpl-progress.show
// GET /mahasiswa/pl-progress         →  mahasiswa.pl-progress
Route::prefix('cpl-progress')->name('cpl-progress.')->group(function () {
    Route::get('/', [CplProgressController::class, 'index'])->name('index');
    Route::get('/{cpl}', [CplProgressController::class, 'show'])->name('show');
});
Route::get('/pl-progress', [CplProgressController::class, 'plProgress'])->name('pl-progress');

// ── RPS (read-only — MK yang diikuti) ────────────────────────────────────────
// GET /mahasiswa/rps/{mk}/{semester}  →  mahasiswa.rps.show
Route::prefix('rps')->name('rps.')->group(function () {
    Route::get('/{mk}/{semester}', [DashboardController::class, 'rpsShow'])->name('show');
});

// ── Materi Pembelajaran (download) ───────────────────────────────────────────
// GET /mahasiswa/materi               →  mahasiswa.materi.index
// GET /mahasiswa/materi/{materi}      →  mahasiswa.materi.show  (download/preview)
Route::resource('materi', MateriController::class)
    ->only(['index', 'show']);
