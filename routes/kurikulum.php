<?php

use App\Http\Controllers\Kurikulum\ArsipRapatController;
use App\Http\Controllers\Kurikulum\BahanKajianController;
use App\Http\Controllers\Kurikulum\CplProdiController;
use App\Http\Controllers\Kurikulum\CpmkController;
use App\Http\Controllers\Kurikulum\DashboardController;
use App\Http\Controllers\Kurikulum\DistribusiSemesterController;
use App\Http\Controllers\Kurikulum\GeneratePdfController;
use App\Http\Controllers\Kurikulum\KomentarReviewController;
use App\Http\Controllers\Kurikulum\KurikulumController;
use App\Http\Controllers\Kurikulum\MataKuliahController;
use App\Http\Controllers\Kurikulum\OverviewController;
use App\Http\Controllers\Kurikulum\PivotController;
use App\Http\Controllers\Kurikulum\PlController;
use App\Http\Controllers\Kurikulum\SubCpmkController;
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

        // Arsip Rapat
        // /kurikulum/{kurikulum}/arsip-rapat  →  kurikulum.arsip-rapat.*
        Route::resource('arsip-rapat', ArsipRapatController::class)
            ->names('arsip-rapat');

        // Profil Lulusan
        // /kurikulum/{kurikulum}/pl  →  kurikulum.pl.*
        Route::get('pl/export', [PlController::class, 'export'])->name('pl.export');
        Route::resource('pl', PlController::class)
            ->names('pl');
        Route::post('pl/{pl}/approve', [PlController::class, 'approve'])->name('pl.approve');
        Route::post('pl/batch-approve', [PlController::class, 'batchApprove'])->name('pl.batch-approve');

        // CPL SN-Dikti (data global, diakses dalam konteks kurikulum agar sidebar tetap tampil)
        // /kurikulum/{kurikulum}/cpl-sndikti  →  kurikulum.cpl-sndikti.*
        Route::get('cpl-sndikti',                    [\App\Http\Controllers\Kurikulum\CplSndiktiController::class, 'index'])->name('cpl-sndikti.index');
        Route::get('cpl-sndikti/export',             [\App\Http\Controllers\Kurikulum\CplSndiktiController::class, 'export'])->name('cpl-sndikti.export');
        Route::get('cpl-sndikti/create',             [\App\Http\Controllers\Kurikulum\CplSndiktiController::class, 'create'])->name('cpl-sndikti.create');
        Route::post('cpl-sndikti',                   [\App\Http\Controllers\Kurikulum\CplSndiktiController::class, 'store'])->name('cpl-sndikti.store');
        Route::get('cpl-sndikti/{cplSndikti}/edit',  [\App\Http\Controllers\Kurikulum\CplSndiktiController::class, 'edit'])->name('cpl-sndikti.edit');
        Route::put('cpl-sndikti/{cplSndikti}',       [\App\Http\Controllers\Kurikulum\CplSndiktiController::class, 'update'])->name('cpl-sndikti.update');
        Route::delete('cpl-sndikti/{cplSndikti}',    [\App\Http\Controllers\Kurikulum\CplSndiktiController::class, 'destroy'])->name('cpl-sndikti.destroy');
        Route::post('cpl-sndikti/{cplSndikti}/approve', [\App\Http\Controllers\Kurikulum\CplSndiktiController::class, 'approve'])->name('cpl-sndikti.approve');
        Route::post('cpl-sndikti/batch-approve', [\App\Http\Controllers\Kurikulum\CplSndiktiController::class, 'batchApprove'])->name('cpl-sndikti.batch-approve');

        // CPL Prodi
        // /kurikulum/{kurikulum}/cpl-prodi  →  kurikulum.cpl-prodi.*
        Route::get('cpl-prodi/export', [CplProdiController::class, 'export'])->name('cpl-prodi.export');
        Route::resource('cpl-prodi', CplProdiController::class)
            ->names('cpl-prodi');
        Route::post('cpl-prodi/{cplProdi}/approve', [CplProdiController::class, 'approve'])->name('cpl-prodi.approve');
        Route::post('cpl-prodi/batch-approve', [CplProdiController::class, 'batchApprove'])->name('cpl-prodi.batch-approve');

        // Bahan Kajian
        // /kurikulum/{kurikulum}/bahan-kajian  →  kurikulum.bahan-kajian.*
        Route::get('bahan-kajian/export', [BahanKajianController::class, 'export'])->name('bahan-kajian.export');
        Route::resource('bahan-kajian', BahanKajianController::class)
            ->names('bahan-kajian');
        Route::post('bahan-kajian/{bahanKajian}/approve', [BahanKajianController::class, 'approve'])->name('bahan-kajian.approve');
        Route::post('bahan-kajian/batch-approve', [BahanKajianController::class, 'batchApprove'])->name('bahan-kajian.batch-approve');

        // Mata Kuliah
        // /kurikulum/{kurikulum}/mata-kuliah  →  kurikulum.mata-kuliah.*
        Route::get('mata-kuliah/export', [MataKuliahController::class, 'export'])->name('mata-kuliah.export');
        Route::resource('mata-kuliah', MataKuliahController::class)
            ->names('mata-kuliah');

        // Update semester MK via AJAX (dari Susunan MK)
        Route::patch('mata-kuliah/{mataKuliah}/semester', [MataKuliahController::class, 'updateSemester'])
            ->name('mata-kuliah.update-semester');

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
            Route::get('/pemenuhan-cpl',         [OverviewController::class, 'pemenuhanCpl'])->name('pemenuhan-cpl');
            Route::get('/pemenuhan-cpl/export',  [OverviewController::class, 'exportPemenuhanCpl'])->name('pemenuhan-cpl.export');
            Route::get('/cpmk',                  [OverviewController::class, 'cpmkOverview'])->name('cpmk');
            Route::get('/rumusan-akhir',         [OverviewController::class, 'rumusanAkhir'])->name('rumusan-akhir');
            Route::get('/ketercapaian-pl',       [OverviewController::class, 'ketercapaianPl'])->name('ketercapaian-pl');
            Route::get('/cpl-cpmk-mk',           [OverviewController::class, 'cplCpmkMk'])->name('cpl-cpmk-mk');
            Route::get('/cpl-cpmk-mk/export',    [OverviewController::class, 'exportCplCpmkMk'])->name('cpl-cpmk-mk.export');
            Route::post('/cpl-cpmk-mk',          [OverviewController::class, 'storeCpmk'])->name('cpl-cpmk-mk.store');
            Route::patch('/cpl-cpmk-mk/{cpmk}',  [OverviewController::class, 'updateCpmk'])->name('cpl-cpmk-mk.update');
            Route::delete('/cpl-cpmk-mk/{cpmk}', [OverviewController::class, 'destroyCpmk'])->name('cpl-cpmk-mk.destroy');
        });

        // Organisasi MK per semester
        Route::get('organisasi-mk',         [OverviewController::class,   'organisasiMk'])->name('organisasi-mk');
        Route::get('organisasi-mk/export',  [OverviewController::class,   'exportOrganisasiMk'])->name('organisasi-mk.export');
        Route::post('organisasi-mk', [MataKuliahController::class, 'batchUpdateKategori'])->name('organisasi-mk.save');
        Route::patch('organisasi-mk/{mataKuliah}', [MataKuliahController::class, 'updateKategori'])->name('organisasi-mk.update');

        // ── Asesmen & Penilaian ───────────────────────────────────────────────────
        Route::prefix('penilaian')->name('penilaian.')->group(function () {
            Route::get('/mk-cpmk-subcpmk', [\App\Http\Controllers\Kurikulum\PenilaianController::class, 'mkCpmkSubcpmk'])->name('mk-cpmk-subcpmk');
            Route::get('/mk-cpmk-subcpmk/export', [\App\Http\Controllers\Kurikulum\PenilaianController::class, 'exportMkCpmkSubcpmk'])->name('mk-cpmk-subcpmk.export');
            Route::get('/teknik',         [\App\Http\Controllers\Kurikulum\PenilaianController::class, 'teknikPenilaian'])->name('teknik');
            Route::get('/teknik/export',  [\App\Http\Controllers\Kurikulum\PenilaianController::class, 'exportTeknikPenilaian'])->name('teknik.export');
            Route::post('/teknik',        [\App\Http\Controllers\Kurikulum\PenilaianController::class, 'saveTeknikPenilaian'])->name('teknik.save');
            Route::get('/tahap',          [\App\Http\Controllers\Kurikulum\PenilaianController::class, 'tahapMekanisme'])->name('tahap');
            Route::get('/tahap/export',   [\App\Http\Controllers\Kurikulum\PenilaianController::class, 'exportTahapMekanisme'])->name('tahap.export');
            Route::post('/tahap',         [\App\Http\Controllers\Kurikulum\PenilaianController::class, 'saveTahapMekanisme'])->name('tahap.save');
            Route::get('/bobot',          [\App\Http\Controllers\Kurikulum\PenilaianController::class, 'bobotPenilaian'])->name('bobot');
            Route::get('/bobot/export',   [\App\Http\Controllers\Kurikulum\PenilaianController::class, 'exportBobotPenilaian'])->name('bobot.export');
            Route::post('/bobot',         [\App\Http\Controllers\Kurikulum\PenilaianController::class, 'saveBobotPenilaian'])->name('bobot.save');
            Route::get('/bobot-mk',         [\App\Http\Controllers\Kurikulum\PenilaianController::class, 'bobotMk'])->name('bobot-mk');
            Route::get('/bobot-mk/export',  [\App\Http\Controllers\Kurikulum\PenilaianController::class, 'exportBobotMk'])->name('bobot-mk.export');
            Route::post('/bobot-mk',        [\App\Http\Controllers\Kurikulum\PenilaianController::class, 'saveBobotMk'])->name('bobot-mk.save');
            Route::get('/rumusan',          [\App\Http\Controllers\Kurikulum\PenilaianController::class, 'rumusanNilai'])->name('rumusan');
            Route::get('/rumusan/export',   [\App\Http\Controllers\Kurikulum\PenilaianController::class, 'exportRumusanNilai'])->name('rumusan.export');
            Route::post('/rumusan',         [\App\Http\Controllers\Kurikulum\PenilaianController::class, 'saveRumusanNilai'])->name('rumusan.save');
            Route::get('/rumusan-cpl',         [\App\Http\Controllers\Kurikulum\PenilaianController::class, 'rumusanNilaiCpl'])->name('rumusan-cpl');
            Route::get('/rumusan-cpl/export',  [\App\Http\Controllers\Kurikulum\PenilaianController::class, 'exportRumusanNilaiCpl'])->name('rumusan-cpl.export');
            Route::post('/rumusan-cpl',        [\App\Http\Controllers\Kurikulum\PenilaianController::class, 'saveRumusanNilaiCpl'])->name('rumusan-cpl.save');

            // Peta CPL-CPMK-MK Semester
            Route::get('/peta-semester',         [\App\Http\Controllers\Kurikulum\PenilaianController::class, 'petaSemester'])->name('peta-semester');
            Route::get('/peta-semester/export',  [\App\Http\Controllers\Kurikulum\PenilaianController::class, 'exportPetaSemester'])->name('peta-semester.export');
            Route::post('/peta-semester',        [\App\Http\Controllers\Kurikulum\PenilaianController::class, 'addPetaSemester'])->name('peta-semester.add');
            Route::delete('/peta-semester/{cpmk}', [\App\Http\Controllers\Kurikulum\PenilaianController::class, 'removePetaSemester'])->name('peta-semester.remove');

            // Rekap Nilai per Mata Kuliah
            Route::get('/rekap-nilai', [\App\Http\Controllers\Kurikulum\RekapNilaiController::class, 'index'])->name('rekap-nilai');
            Route::get('/rekap-nilai/{mataKuliah}', [\App\Http\Controllers\Kurikulum\RekapNilaiController::class, 'show'])->name('rekap-nilai.show');
            Route::post('/rekap-nilai/{mataKuliah}', [\App\Http\Controllers\Kurikulum\RekapNilaiController::class, 'store'])->name('rekap-nilai.store');
            Route::get('/rekap-nilai/{mataKuliah}/export', [\App\Http\Controllers\Kurikulum\RekapNilaiController::class, 'export'])->name('rekap-nilai.export');

            // Proses Penilaian & Evaluasi CPL (Tabel K) — drill-down per 1 CPL lintas MK
            Route::get('/evaluasi-cpl', [\App\Http\Controllers\Kurikulum\RekapCplController::class, 'proses'])->name('evaluasi-cpl');
            Route::get('/evaluasi-cpl/export', [\App\Http\Controllers\Kurikulum\RekapCplController::class, 'prosesExport'])->name('evaluasi-cpl.export');
            Route::post('/evaluasi-cpl/import', [\App\Http\Controllers\Kurikulum\RekapCplController::class, 'prosesImport'])->name('evaluasi-cpl.import');

            // Rekap Capaian CPL (Tabel L) — matriks lintas MK dalam 1 semester
            Route::get('/rekap-cpl', [\App\Http\Controllers\Kurikulum\RekapCplController::class, 'index'])->name('rekap-cpl');
            Route::get('/rekap-cpl/export', [\App\Http\Controllers\Kurikulum\RekapCplController::class, 'export'])->name('rekap-cpl.export');
            Route::post('/rekap-cpl/import', [\App\Http\Controllers\Kurikulum\RekapCplController::class, 'import'])->name('rekap-cpl.import');
        });

        // Generate PDF / Print
        Route::get('generate-pdf', [GeneratePdfController::class, 'dokumenKurikulum'])->name('generate-pdf');

        // ── Pivot Matriks ─────────────────────────────────────────────────────
        // Setiap endpoint pivot menerima GET (tampil matriks) dan POST (simpan centang)
        Route::prefix('pivot')->name('pivot.')->group(function () {

            // Auto-save AJAX per-cell + sinkronisasi matriks turunan
            Route::post('/toggle', [PivotController::class, 'toggle'])->name('toggle');

            // Matriks PL ↔ CPL Prodi
            Route::get('/pl-cpl', [PivotController::class, 'plCpl'])->name('pl-cpl');
            Route::get('/pl-cpl/export', [PivotController::class, 'exportPlCpl'])->name('pl-cpl.export');
            Route::post('/pl-cpl', [PivotController::class, 'savePlCpl'])->name('pl-cpl.save');

            // Matriks CPL SN-Dikti ↔ CPL Prodi
            Route::get('/cplsn-cplp', [PivotController::class, 'cplsnCplp'])->name('cplsn-cplp');
            Route::get('/cplsn-cplp/export', [PivotController::class, 'exportCplsnCplp'])->name('cplsn-cplp.export');
            Route::post('/cplsn-cplp', [PivotController::class, 'saveCplsnCplp'])->name('cplsn-cplp.save');

            // Matriks CPL Prodi ↔ Bahan Kajian
            Route::get('/cpl-bk', [PivotController::class, 'cplBk'])->name('cpl-bk');
            Route::get('/cpl-bk/export', [PivotController::class, 'exportCplBk'])->name('cpl-bk.export');
            Route::post('/cpl-bk', [PivotController::class, 'saveCplBk'])->name('cpl-bk.save');

            // Matriks MK ↔ Bahan Kajian
            Route::get('/mk-bk', [PivotController::class, 'mkBk'])->name('mk-bk');
            Route::get('/mk-bk/export', [PivotController::class, 'exportMkBk'])->name('mk-bk.export');
            Route::post('/mk-bk', [PivotController::class, 'saveMkBk'])->name('mk-bk.save');

            // Matriks MK ↔ CPL (via CPMK)
            Route::get('/mk-cpl', [PivotController::class, 'mkCpl'])->name('mk-cpl');
            Route::get('/mk-cpl/export', [PivotController::class, 'exportMkCpl'])->name('mk-cpl.export');
            Route::post('/mk-cpl', [PivotController::class, 'saveMkCpl'])->name('mk-cpl.save');

            // Matriks 3 dimensi CPL ↔ BK ↔ MK (auto-derived, read-only — sync manual via POST)
            Route::get('/cpl-bk-mk', [PivotController::class, 'cplBkMk'])->name('cpl-bk-mk');
            Route::get('/cpl-bk-mk/export', [PivotController::class, 'exportCplBkMk'])->name('cpl-bk-mk.export');
            Route::post('/cpl-bk-mk/sync', [PivotController::class, 'syncCplBkMk'])->name('cpl-bk-mk.sync');

        });
    });

// ── Komentar Review (lintas kurikulum, tanpa prefix /{kurikulum}) ─────────────
Route::post('/komentar', [KomentarReviewController::class, 'store'])->name('komentar.store');
Route::post('/komentar/{komentar}/resolve', [KomentarReviewController::class, 'resolve'])->name('komentar.resolve');
Route::delete('/komentar/{komentar}', [KomentarReviewController::class, 'destroy'])->name('komentar.destroy');
