<?php

use App\Http\Controllers\Kurikulum\ArsipRapatController;
use App\Http\Controllers\Kurikulum\BahanKajianController;
use App\Http\Controllers\Kurikulum\ChecklistLedikController;
use App\Http\Controllers\Kurikulum\CplProdiController;
use App\Http\Controllers\Kurikulum\CpmkController;
use App\Http\Controllers\Kurikulum\DashboardController;
use App\Http\Controllers\Kurikulum\DistribusiDokumenController;
use App\Http\Controllers\Kurikulum\DistribusiSemesterController;
use App\Http\Controllers\Kurikulum\GeneratePdfController;
use App\Http\Controllers\Kurikulum\KomentarReviewController;
use App\Http\Controllers\Kurikulum\KurikulumController;
use App\Http\Controllers\Kurikulum\LessonLearnedController;
use App\Http\Controllers\Kurikulum\MataKuliahController;
use App\Http\Controllers\Kurikulum\OverviewController;
use App\Http\Controllers\Kurikulum\PeriodeKurikulumController;
use App\Http\Controllers\Kurikulum\PivotController;
use App\Http\Controllers\Kurikulum\PlController;
use App\Http\Controllers\Kurikulum\SubCpmkController;
use App\Http\Controllers\Kurikulum\TimKurikulumController;
use Illuminate\Support\Facades\Route;

// ── Dashboard ─────────────────────────────────────────────────────────────────
// GET /kurikulum/dashboard  →  kurikulum.dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// ── Kurikulum Master (list & CRUD) ────────────────────────────────────────────
// GET    /kurikulum               →  kurikulum.index
// GET    /kurikulum/create        →  kurikulum.create
// POST   /kurikulum               →  kurikulum.store
// GET    /kurikulum/{kurikulum}   →  kurikulum.show
// GET    /kurikulum/{kurikulum}/edit  →  kurikulum.edit
// PUT    /kurikulum/{kurikulum}   →  kurikulum.update
// DELETE /kurikulum/{kurikulum}   →  kurikulum.destroy
Route::get('/', [KurikulumController::class, 'index'])->name('index');
Route::get('/create', [KurikulumController::class, 'create'])->name('create');
Route::post('/', [KurikulumController::class, 'store'])->name('store');

// Kurikulum show/edit/update/delete — wildcard {kurikulum} harus setelah semua static routes
Route::get('/{kurikulum}', [KurikulumController::class, 'show'])->name('show');
Route::get('/{kurikulum}/edit', [KurikulumController::class, 'edit'])->name('edit');
Route::put('/{kurikulum}', [KurikulumController::class, 'update'])->name('update');
Route::delete('/{kurikulum}', [KurikulumController::class, 'destroy'])->name('destroy');

// Aksi khusus kurikulum — hanya kaprodi (dijaga di controller via Gate/Policy)
Route::post('/{kurikulum}/arsip', [KurikulumController::class, 'arsip'])->name('arsip');
Route::post('/{kurikulum}/aktifkan', [KurikulumController::class, 'aktifkan'])->name('aktifkan');
Route::post('/{kurikulum}/clone', [KurikulumController::class, 'clone'])->name('clone');

// ── Resource terikat kurikulum tertentu ───────────────────────────────────────
// Semua route di bawah menggunakan prefix /{kurikulum}/ dan middleware kurikulum.locked
// (blokir write jika kurikulum berstatus 'arsip')
Route::prefix('/{kurikulum}')
    ->middleware('kurikulum.locked')
    ->group(function () {

        // Periode Penyusunan Kurikulum
        // /kurikulum/{kurikulum}/periode  →  kurikulum.periode.*
        Route::resource('periode', PeriodeKurikulumController::class)
            ->names('periode');

        // Tim Penyusun Kurikulum
        // /kurikulum/{kurikulum}/tim  →  kurikulum.tim.*
        Route::resource('tim', TimKurikulumController::class)
            ->names('tim');

        // Arsip Rapat
        // /kurikulum/{kurikulum}/arsip-rapat  →  kurikulum.arsip-rapat.*
        Route::resource('arsip-rapat', ArsipRapatController::class)
            ->names('arsip-rapat');

        // Profil Lulusan
        // /kurikulum/{kurikulum}/pl  →  kurikulum.pl.*
        Route::resource('pl', PlController::class)
            ->names('pl');
        Route::post('pl/{pl}/approve', [PlController::class, 'approve'])->name('pl.approve');

        // CPL SN-Dikti (data global, diakses dalam konteks kurikulum agar sidebar tetap tampil)
        // /kurikulum/{kurikulum}/cpl-sndikti  →  kurikulum.cpl-sndikti.*
        Route::get('cpl-sndikti',                    [\App\Http\Controllers\Kurikulum\CplSndiktiController::class, 'index'])->name('cpl-sndikti.index');
        Route::get('cpl-sndikti/create',             [\App\Http\Controllers\Kurikulum\CplSndiktiController::class, 'create'])->name('cpl-sndikti.create');
        Route::post('cpl-sndikti',                   [\App\Http\Controllers\Kurikulum\CplSndiktiController::class, 'store'])->name('cpl-sndikti.store');
        Route::get('cpl-sndikti/{cplSndikti}/edit',  [\App\Http\Controllers\Kurikulum\CplSndiktiController::class, 'edit'])->name('cpl-sndikti.edit');
        Route::put('cpl-sndikti/{cplSndikti}',       [\App\Http\Controllers\Kurikulum\CplSndiktiController::class, 'update'])->name('cpl-sndikti.update');
        Route::delete('cpl-sndikti/{cplSndikti}',    [\App\Http\Controllers\Kurikulum\CplSndiktiController::class, 'destroy'])->name('cpl-sndikti.destroy');

        // CPL Prodi
        // /kurikulum/{kurikulum}/cpl-prodi  →  kurikulum.cpl-prodi.*
        Route::resource('cpl-prodi', CplProdiController::class)
            ->names('cpl-prodi');

        // Bahan Kajian
        // /kurikulum/{kurikulum}/bahan-kajian  →  kurikulum.bahan-kajian.*
        Route::resource('bahan-kajian', BahanKajianController::class)
            ->names('bahan-kajian');

        // Mata Kuliah
        // /kurikulum/{kurikulum}/mata-kuliah  →  kurikulum.mata-kuliah.*
        Route::resource('mata-kuliah', MataKuliahController::class)
            ->names('mata-kuliah');

        // CPMK (nested di bawah Mata Kuliah)
        // /kurikulum/{kurikulum}/mata-kuliah/{mataKuliah}/cpmk  →  kurikulum.mata-kuliah.cpmk.*
        Route::resource('mata-kuliah.cpmk', CpmkController::class)
            ->names('mata-kuliah.cpmk');

        // Sub-CPMK (nested di bawah CPMK)
        // /kurikulum/{kurikulum}/mata-kuliah/{mataKuliah}/cpmk/{cpmk}/sub-cpmk  →  kurikulum.mata-kuliah.cpmk.sub-cpmk.*
        Route::resource('mata-kuliah.cpmk.sub-cpmk', SubCpmkController::class)
            ->names('mata-kuliah.cpmk.sub-cpmk');

        // Distribusi Semester (read-only; diperbarui otomatis lewat observer)
        // GET /kurikulum/{kurikulum}/distribusi-semester  →  kurikulum.distribusi-semester
        Route::get('distribusi-semester', [DistribusiSemesterController::class, 'index'])
            ->name('distribusi-semester');

        // ── Overview / Report Pages (read-only summary) ───────────────────────
        Route::prefix('overview')->name('overview.')->group(function () {
            Route::get('/pemenuhan-cpl',    [OverviewController::class, 'pemenuhanCpl'])->name('pemenuhan-cpl');
            Route::get('/cpmk',             [OverviewController::class, 'cpmkOverview'])->name('cpmk');
            Route::get('/rumusan-akhir',    [OverviewController::class, 'rumusanAkhir'])->name('rumusan-akhir');
            Route::get('/ketercapaian-pl',  [OverviewController::class, 'ketercapaianPl'])->name('ketercapaian-pl');
            Route::get('/cpl-cpmk-mk',      [OverviewController::class, 'cplCpmkMk'])->name('cpl-cpmk-mk');
        });

        // Organisasi MK per semester
        Route::get('organisasi-mk', [OverviewController::class, 'organisasiMk'])->name('organisasi-mk');

        // ── Asesmen & Penilaian ───────────────────────────────────────────────────
        Route::prefix('penilaian')->name('penilaian.')->group(function () {
            Route::get('/mk-cpmk-subcpmk', [\App\Http\Controllers\Kurikulum\PenilaianController::class, 'mkCpmkSubcpmk'])->name('mk-cpmk-subcpmk');
            Route::get('/teknik',  [\App\Http\Controllers\Kurikulum\PenilaianController::class, 'teknikPenilaian'])->name('teknik');
            Route::post('/teknik', [\App\Http\Controllers\Kurikulum\PenilaianController::class, 'saveTeknikPenilaian'])->name('teknik.save');
            Route::get('/tahap',   [\App\Http\Controllers\Kurikulum\PenilaianController::class, 'tahapMekanisme'])->name('tahap');
            Route::post('/tahap',  [\App\Http\Controllers\Kurikulum\PenilaianController::class, 'saveTahapMekanisme'])->name('tahap.save');
            Route::get('/bobot',   [\App\Http\Controllers\Kurikulum\PenilaianController::class, 'bobotPenilaian'])->name('bobot');
            Route::post('/bobot',  [\App\Http\Controllers\Kurikulum\PenilaianController::class, 'saveBobotPenilaian'])->name('bobot.save');
            Route::get('/rumusan',     [\App\Http\Controllers\Kurikulum\PenilaianController::class, 'rumusanNilai'])->name('rumusan');
            Route::post('/rumusan',    [\App\Http\Controllers\Kurikulum\PenilaianController::class, 'saveRumusanNilai'])->name('rumusan.save');
            Route::get('/rumusan-cpl', [\App\Http\Controllers\Kurikulum\PenilaianController::class, 'rumusanNilaiCpl'])->name('rumusan-cpl');
            Route::post('/rumusan-cpl',[\App\Http\Controllers\Kurikulum\PenilaianController::class, 'saveRumusanNilaiCpl'])->name('rumusan-cpl.save');

            // Peta CPL-CPMK-MK Semester
            Route::get('/peta-semester',    [\App\Http\Controllers\Kurikulum\PenilaianController::class, 'petaSemester'])->name('peta-semester');
            Route::post('/peta-semester',   [\App\Http\Controllers\Kurikulum\PenilaianController::class, 'addPetaSemester'])->name('peta-semester.add');
            Route::delete('/peta-semester/{cpmk}', [\App\Http\Controllers\Kurikulum\PenilaianController::class, 'removePetaSemester'])->name('peta-semester.remove');
        });

        // Lesson Learned
        Route::resource('lesson-learned', LessonLearnedController::class)
            ->names('lesson-learned');

        // Checklist LEDIK
        Route::get('ledik',  [ChecklistLedikController::class, 'index'])->name('ledik.index');
        Route::post('ledik', [ChecklistLedikController::class, 'update'])->name('ledik.update');

        // Distribusi Dokumen
        Route::get('distribusi',  [DistribusiDokumenController::class, 'index'])->name('distribusi.index');
        Route::post('distribusi', [DistribusiDokumenController::class, 'store'])->name('distribusi.store');

        // Generate PDF / Print
        Route::get('generate-pdf', [GeneratePdfController::class, 'dokumenKurikulum'])->name('generate-pdf');

        // ── Pivot Matriks ─────────────────────────────────────────────────────
        // Setiap endpoint pivot menerima GET (tampil matriks) dan POST (simpan centang)
        Route::prefix('pivot')->name('pivot.')->group(function () {

            // Auto-save AJAX per-cell + sinkronisasi matriks turunan
            Route::post('/toggle', [PivotController::class, 'toggle'])->name('toggle');

            // Matriks PL ↔ CPL Prodi
            Route::get('/pl-cpl', [PivotController::class, 'plCpl'])->name('pl-cpl');
            Route::post('/pl-cpl', [PivotController::class, 'savePlCpl'])->name('pl-cpl.save');

            // Matriks CPL SN-Dikti ↔ CPL Prodi
            Route::get('/cplsn-cplp', [PivotController::class, 'cplsnCplp'])->name('cplsn-cplp');
            Route::post('/cplsn-cplp', [PivotController::class, 'saveCplsnCplp'])->name('cplsn-cplp.save');

            // Matriks CPL Prodi ↔ Bahan Kajian
            Route::get('/cpl-bk', [PivotController::class, 'cplBk'])->name('cpl-bk');
            Route::post('/cpl-bk', [PivotController::class, 'saveCplBk'])->name('cpl-bk.save');

            // Matriks MK ↔ Bahan Kajian
            Route::get('/mk-bk', [PivotController::class, 'mkBk'])->name('mk-bk');
            Route::post('/mk-bk', [PivotController::class, 'saveMkBk'])->name('mk-bk.save');

            // Matriks MK ↔ CPL (via CPMK)
            Route::get('/mk-cpl', [PivotController::class, 'mkCpl'])->name('mk-cpl');
            Route::post('/mk-cpl', [PivotController::class, 'saveMkCpl'])->name('mk-cpl.save');

            // Matriks 3 dimensi CPL ↔ BK ↔ MK
            Route::get('/cpl-bk-mk', [PivotController::class, 'cplBkMk'])->name('cpl-bk-mk');
            Route::post('/cpl-bk-mk', [PivotController::class, 'saveCplBkMk'])->name('cpl-bk-mk.save');

            // Matriks CPL Prodi ↔ CPMK (per CPMK terhadap CPL yang relevan)
            Route::get('/cpl-cpmk', [PivotController::class, 'cplCpmk'])->name('cpl-cpmk');
        });
    });

// ── Komentar Review (lintas kurikulum, tanpa prefix /{kurikulum}) ─────────────
Route::post('/komentar', [KomentarReviewController::class, 'store'])->name('komentar.store');
Route::post('/komentar/{komentar}/resolve', [KomentarReviewController::class, 'resolve'])->name('komentar.resolve');
Route::delete('/komentar/{komentar}', [KomentarReviewController::class, 'destroy'])->name('komentar.destroy');
