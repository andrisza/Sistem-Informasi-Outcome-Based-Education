<?php

use App\Http\Controllers\Kaprodi\ActivityLogController;
use App\Http\Controllers\Kaprodi\EnrollmentMkController;
use App\Http\Controllers\Kaprodi\DashboardController;
use App\Http\Controllers\Kaprodi\PengampuanMkController;
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
// GET  /kaprodi/rps-approval                   →  kaprodi.rps-approval.index
// GET  /kaprodi/rps-approval/{rps}             →  kaprodi.rps-approval.show
// POST /kaprodi/rps-approval/{rps}/approve     →  kaprodi.rps-approval.approve
// POST /kaprodi/rps-approval/{rps}/reject      →  kaprodi.rps-approval.reject
// POST /kaprodi/rps-approval/batch-approve     →  kaprodi.rps-approval.batch-approve
// POST /kaprodi/rps-approval/batch-draft       →  kaprodi.rps-approval.batch-draft
Route::prefix('rps-approval')->name('rps-approval.')->group(function () {
    Route::get('/', [RpsApprovalController::class, 'index'])->name('index');
    Route::post('/batch-approve', [RpsApprovalController::class, 'batchApprove'])->name('batch-approve');
    Route::post('/batch-draft',   [RpsApprovalController::class, 'batchDraft'])->name('batch-draft');
    Route::get('/{rps}', [RpsApprovalController::class, 'show'])->name('show');
    Route::post('/{rps}/approve', [RpsApprovalController::class, 'approve'])->name('approve');
    Route::post('/{rps}/reject',  [RpsApprovalController::class, 'reject'])->name('reject');
});

// ── Pengampuan MK ─────────────────────────────────────────────────────────────
// Kaprodi menugaskan Dosen ke MK per Semester agar Dosen bisa membuat RPS.
// GET    /kaprodi/pengampuan             →  kaprodi.pengampuan.index
// GET    /kaprodi/pengampuan/create      →  kaprodi.pengampuan.create  (satu dosen)
// POST   /kaprodi/pengampuan             →  kaprodi.pengampuan.store
// POST   /kaprodi/pengampuan/batch       →  kaprodi.pengampuan.store-batch (batch)
// POST   /kaprodi/pengampuan/{p}/koord   →  kaprodi.pengampuan.toggle-koord
// DELETE /kaprodi/pengampuan/{p}         →  kaprodi.pengampuan.destroy
Route::prefix('pengampuan')->name('pengampuan.')->group(function () {
    Route::get('/',             [PengampuanMkController::class, 'index'])->name('index');
    Route::get('/create',       [PengampuanMkController::class, 'create'])->name('create');
    Route::post('/',            [PengampuanMkController::class, 'store'])->name('store');
    Route::post('/batch',       [PengampuanMkController::class, 'storeBatch'])->name('store-batch');
    Route::post('/{pengampuan}/toggle-koord', [PengampuanMkController::class, 'toggleKoordinator'])->name('toggle-koord');
    Route::delete('/{pengampuan}', [PengampuanMkController::class, 'destroy'])->name('destroy');
});

// ── Enrollment MK (Daftar Mahasiswa ke MK per Semester) ──────────────────────
// GET    /kaprodi/enrollment                →  kaprodi.enrollment.index
// GET    /kaprodi/enrollment/create         →  kaprodi.enrollment.create (satu mahasiswa)
// POST   /kaprodi/enrollment                →  kaprodi.enrollment.store
// GET    /kaprodi/enrollment/batch          →  kaprodi.enrollment.batch   (banyak mahasiswa)
// POST   /kaprodi/enrollment/batch          →  kaprodi.enrollment.store-batch
// GET    /kaprodi/enrollment/{e}/edit       →  kaprodi.enrollment.edit
// PUT    /kaprodi/enrollment/{e}            →  kaprodi.enrollment.update
// DELETE /kaprodi/enrollment/{e}            →  kaprodi.enrollment.destroy
Route::prefix('enrollment')->name('enrollment.')->group(function () {
    Route::get('/',              [EnrollmentMkController::class, 'index'])->name('index');
    Route::get('/create',        [EnrollmentMkController::class, 'create'])->name('create');
    Route::post('/',             [EnrollmentMkController::class, 'store'])->name('store');
    Route::get('/batch',         [EnrollmentMkController::class, 'batchCreate'])->name('batch');
    Route::post('/batch',        [EnrollmentMkController::class, 'storeBatch'])->name('store-batch');
    Route::get('/{enrollment}/edit',  [EnrollmentMkController::class, 'edit'])->name('edit');
    Route::put('/{enrollment}',       [EnrollmentMkController::class, 'update'])->name('update');
    Route::delete('/{enrollment}',    [EnrollmentMkController::class, 'destroy'])->name('destroy');
});

// ── Audit Trail ───────────────────────────────────────────────────────────────
// GET  /kaprodi/activity-log  →  kaprodi.activity-log.index
Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');

// ── Master Kategori ───────────────────────────────────────────────────────────
// GET  /kaprodi/master-kategori               →  kaprodi.master-kategori.index
// POST /kaprodi/master-kategori               →  kaprodi.master-kategori.store
// PUT  /kaprodi/master-kategori/{masterKategori}          →  kaprodi.master-kategori.update
// POST /kaprodi/master-kategori/{masterKategori}/toggle   →  kaprodi.master-kategori.toggle
use App\Http\Controllers\Kaprodi\MasterKategoriController;

Route::prefix('master-kategori')->name('master-kategori.')->group(function () {
    Route::get('/', [MasterKategoriController::class, 'index'])->name('index');
    Route::post('/', [MasterKategoriController::class, 'store'])->name('store');
    Route::post('/jenis', [MasterKategoriController::class, 'storeJenis'])->name('store-jenis');
    Route::put('/{masterKategori}', [MasterKategoriController::class, 'update'])->name('update');
    Route::post('/{masterKategori}/toggle', [MasterKategoriController::class, 'toggleAktif'])->name('toggle');
});
