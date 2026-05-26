<?php

use App\Http\Controllers\Kaprodi\ActivityLogController;
use App\Http\Controllers\Kaprodi\CqiController;
use App\Http\Controllers\Kaprodi\DashboardController;
use App\Http\Controllers\Kaprodi\ReportController;
use App\Http\Controllers\Kaprodi\RpsApprovalController;
use App\Http\Controllers\Kaprodi\SemesterAkademikController;
use App\Http\Controllers\Kaprodi\UserController;
use Illuminate\Support\Facades\Route;

// ── Dashboard ─────────────────────────────────────────────────────────────────
// GET /kaprodi/dashboard  →  kaprodi.dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// ── Manajemen User (CRUD semua peran) ─────────────────────────────────────────
// GET    /kaprodi/users            →  kaprodi.users.index
// GET    /kaprodi/users/create     →  kaprodi.users.create
// POST   /kaprodi/users            →  kaprodi.users.store
// GET    /kaprodi/users/{user}     →  kaprodi.users.show
// GET    /kaprodi/users/{user}/edit→  kaprodi.users.edit
// PUT    /kaprodi/users/{user}     →  kaprodi.users.update
// DELETE /kaprodi/users/{user}     →  kaprodi.users.destroy
// POST   /kaprodi/users/{user}/toggle-status  →  kaprodi.users.toggle-status
Route::resource('users', UserController::class);
Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
    ->name('users.toggle-status');

// ── Semester Akademik ─────────────────────────────────────────────────────────
// GET    /kaprodi/semester-akademik          →  kaprodi.semester-akademik.index
// POST   /kaprodi/semester-akademik          →  kaprodi.semester-akademik.store
// PUT    /kaprodi/semester-akademik/{id}     →  kaprodi.semester-akademik.update
// POST   /kaprodi/semester-akademik/{id}/aktifkan → kaprodi.semester-akademik.aktifkan
Route::resource('semester-akademik', SemesterAkademikController::class)
    ->except(['show']);
Route::post('/semester-akademik/{semesterAkademik}/aktifkan', [SemesterAkademikController::class, 'aktifkan'])
    ->name('semester-akademik.aktifkan');

// ── Persetujuan RPS ───────────────────────────────────────────────────────────
// GET  /kaprodi/rps-approval            →  kaprodi.rps-approval.index
// GET  /kaprodi/rps-approval/{rps}      →  kaprodi.rps-approval.show
// POST /kaprodi/rps-approval/{rps}/approve  →  kaprodi.rps-approval.approve
// POST /kaprodi/rps-approval/{rps}/reject   →  kaprodi.rps-approval.reject
Route::prefix('rps-approval')->name('rps-approval.')->group(function () {
    Route::get('/', [RpsApprovalController::class, 'index'])->name('index');
    Route::get('/{rps}', [RpsApprovalController::class, 'show'])->name('show');
    Route::post('/{rps}/approve', [RpsApprovalController::class, 'approve'])->name('approve');
    Route::post('/{rps}/reject', [RpsApprovalController::class, 'reject'])->name('reject');
});

// ── CQI – Evaluasi & Tindak Lanjut ───────────────────────────────────────────
// GET  /kaprodi/cqi            →  kaprodi.cqi.index
// GET  /kaprodi/cqi/{log}      →  kaprodi.cqi.show
// POST /kaprodi/cqi/{log}/setujui  →  kaprodi.cqi.setujui
// PUT  /kaprodi/cqi/{log}      →  kaprodi.cqi.update  (ubah status)
Route::prefix('cqi')->name('cqi.')->group(function () {
    Route::get('/', [CqiController::class, 'index'])->name('index');
    Route::get('/{log}', [CqiController::class, 'show'])->name('show');
    Route::post('/{log}/setujui', [CqiController::class, 'setujui'])->name('setujui');
    Route::patch('/{log}', [CqiController::class, 'update'])->name('update');
});

// ── Laporan OBE ───────────────────────────────────────────────────────────────
// GET  /kaprodi/reports                       →  kaprodi.reports.index
// GET  /kaprodi/reports/cpl/{kurikulum}       →  kaprodi.reports.cpl
// GET  /kaprodi/reports/pl/{kurikulum}        →  kaprodi.reports.pl
// GET  /kaprodi/reports/ketercapaian          →  kaprodi.reports.ketercapaian
Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('index');
    Route::get('/cpl/{kurikulum}', [ReportController::class, 'cpl'])->name('cpl');
    Route::get('/pl/{kurikulum}', [ReportController::class, 'pl'])->name('pl');
    Route::get('/ketercapaian', [ReportController::class, 'ketercapaian'])->name('ketercapaian');
});

// ── Audit Trail ───────────────────────────────────────────────────────────────
// GET  /kaprodi/activity-log  →  kaprodi.activity-log.index
Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
