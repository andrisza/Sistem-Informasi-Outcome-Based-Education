<?php

use App\Http\Controllers\Dosen\DashboardController;
use App\Http\Controllers\Dosen\JurnalMengajarController;
use App\Http\Controllers\Dosen\MateriController;
use App\Http\Controllers\Dosen\NilaiController;
use App\Http\Controllers\Dosen\RpsController;
use Illuminate\Support\Facades\Route;

// ── Dashboard ─────────────────────────────────────────────────────────────────
// GET /dosen/dashboard  →  dosen.dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// ── Pengampuan (MK yang saya ampu semester ini) ───────────────────────────────
// GET /dosen/pengampuan  →  dosen.pengampuan
Route::get('/pengampuan', [DashboardController::class, 'pengampuan'])->name('pengampuan');

// ── RPS (Rencana Pembelajaran Semester) ───────────────────────────────────────
// GET    /dosen/rps              →  dosen.rps.index    (daftar RPS saya)
// GET    /dosen/rps/create       →  dosen.rps.create
// POST   /dosen/rps              →  dosen.rps.store
// GET    /dosen/rps/{rps}        →  dosen.rps.show
// GET    /dosen/rps/{rps}/edit   →  dosen.rps.edit
// PUT    /dosen/rps/{rps}        →  dosen.rps.update
// DELETE /dosen/rps/{rps}        →  dosen.rps.destroy
// POST   /dosen/rps/{rps}/submit →  dosen.rps.submit  (ajukan ke kaprodi)
// Parameter name dipaksa jadi {rps} agar cocok dengan $rps di controller.
// Tanpa ini, Laravel mengsingularkan "rps" → "rp" sehingga model binding gagal.
Route::resource('rps', RpsController::class)->parameters(['rps' => 'rps']);
Route::post('/rps/{rps}/submit', [RpsController::class, 'submit'])->name('rps.submit');
Route::get('/rps/{rps}/print',   [RpsController::class, 'print'])->name('rps.print');

// ── Pertemuan RPS ─────────────────────────────────────────────────────────────
// GET  /dosen/rps/{rps}/pertemuan          →  dosen.rps.pertemuan.index
// GET  /dosen/rps/{rps}/pertemuan/{minggu} →  dosen.rps.pertemuan.show
// PUT  /dosen/rps/{rps}/pertemuan/{minggu} →  dosen.rps.pertemuan.update
Route::prefix('/rps/{rps}/pertemuan')->name('rps.pertemuan.')->group(function () {
    Route::get('/', [RpsController::class, 'pertemuanIndex'])->name('index');
    Route::get('/{minggu}', [RpsController::class, 'pertemuanShow'])->name('show');
    Route::put('/{minggu}', [RpsController::class, 'pertemuanUpdate'])->name('update');
});

// ── Jurnal Mengajar ───────────────────────────────────────────────────────────
// GET  /dosen/jurnal           →  dosen.jurnal.index
// GET  /dosen/jurnal/create    →  dosen.jurnal.create
// POST /dosen/jurnal           →  dosen.jurnal.store
// GET  /dosen/jurnal/{jurnal}  →  dosen.jurnal.show
// PUT  /dosen/jurnal/{jurnal}  →  dosen.jurnal.update
Route::resource('jurnal', JurnalMengajarController::class)
    ->except(['destroy']);

// ── Input Nilai Mahasiswa ─────────────────────────────────────────────────────
// GET  /dosen/nilai                   →  dosen.nilai.index  (pilih MK)
// GET  /dosen/nilai/{mk}/{semester}   →  dosen.nilai.form   (form input nilai)
// POST /dosen/nilai/{mk}/{semester}   →  dosen.nilai.store  (simpan batch)
// GET  /dosen/nilai/{mk}/{semester}/rekap  →  dosen.nilai.rekap
Route::prefix('nilai')->name('nilai.')->group(function () {
    Route::get('/', [NilaiController::class, 'index'])->name('index');
    Route::get('/{mk}/{semester}', [NilaiController::class, 'form'])->name('form');
    Route::post('/{mk}/{semester}', [NilaiController::class, 'store'])->name('store');
    Route::get('/{mk}/{semester}/rekap', [NilaiController::class, 'rekap'])->name('rekap');
});

// ── Repositori Materi ─────────────────────────────────────────────────────────
// GET    /dosen/materi            →  dosen.materi.index
// GET    /dosen/materi/create     →  dosen.materi.create
// POST   /dosen/materi            →  dosen.materi.store
// DELETE /dosen/materi/{materi}   →  dosen.materi.destroy
Route::resource('materi', MateriController::class)
    ->only(['index', 'create', 'store', 'destroy']);
