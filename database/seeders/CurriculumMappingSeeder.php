<?php

namespace Database\Seeders;

use App\Models\BahanKajian;
use App\Models\Cpmk;
use App\Models\CplProdi;
use App\Models\Kurikulum;
use App\Models\MataKuliah;
use App\Models\Pl;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seed data pemetaan kurikulum sesuai spreadsheet "Rancangan Kurikulum SI Gasal 24-25".
 *
 * Tugas seeder ini:
 *  1. Tambah 15 MK Pilihan (MK52–MK66) yang belum ada di sistem
 *  2. Update pivot_pl_cpl sesuai Sheet 5 (CPL-PL) — BUKAN Copy Sheet
 *  3. Update pivot_cpl_bk sesuai Sheet 7 (CPL-BK)
 *  4. Update pivot_mk_bk sesuai Sheet 8 (BK-MK) untuk semua 66 MK
 *  5. Sync MatrixConsistencyService → pivot_cpl_bk_mk + pivot_mk_cpl
 *  6. Seed CPMK + Sub-CPMK untuk 15 MK baru
 *
 * Jalankan dengan: php artisan db:seed --class=CurriculumMappingSeeder
 */
class CurriculumMappingSeeder extends Seeder
{
    public function run(): void
    {
        $kur = Kurikulum::where('kode', 'K-SI-S1-2021')->firstOrFail();

        $this->command->info("Kurikulum: {$kur->kode} (id={$kur->id})");

        // ── 1. Tambah 15 MK Pilihan ───────────────────────────────────────────
        $this->command->info('Step 1: Menambah 15 MK Pilihan...');
        $this->seedMkPilihan($kur);

        // ── 2. Update pivot CPL-PL ────────────────────────────────────────────
        $this->command->info('Step 2: Update pivot CPL-PL dari spreadsheet...');
        $this->updatePivotPlCpl($kur);

        // ── 3. Update pivot CPL-BK ────────────────────────────────────────────
        $this->command->info('Step 3: Update pivot CPL-BK dari spreadsheet...');
        $this->updatePivotCplBk($kur);

        // ── 4. Update pivot MK-BK (semua 66 MK) ──────────────────────────────
        $this->command->info('Step 4: Update pivot MK-BK dari spreadsheet...');
        $this->updatePivotMkBk($kur);

        // ── 5. Sync matriks turunan ───────────────────────────────────────────
        $this->command->info('Step 5: Sync MatrixConsistencyService...');
        app(\App\Services\MatrixConsistencyService::class)->syncAll($kur);

        // ── 6. Seed CPMK untuk MK baru ────────────────────────────────────────
        $this->command->info('Step 6: Seed CPMK + Sub-CPMK untuk MK baru...');
        $sem = \App\Models\SemesterAkademik::where('is_aktif', 1)->first();
        (new CpmkDataSeeder())->seedFor($kur, $sem);

        $this->command->info('✅ Pemetaan kurikulum selesai!');
        $this->command->table(['Entitas', 'Jumlah'], [
            ['MK Total',        MataKuliah::where('id_kurikulum', $kur->id)->count()],
            ['pivot_pl_cpl',    DB::table('pivot_pl_cpl')->whereIn('id_pl', $kur->pl()->pluck('id'))->count()],
            ['pivot_cpl_bk',    DB::table('pivot_cpl_bk')->whereIn('id_cpl', $kur->cplProdi()->pluck('id'))->count()],
            ['pivot_mk_bk',     DB::table('pivot_mk_bk')->whereIn('id_mk', $kur->mataKuliah()->pluck('id'))->count()],
            ['pivot_cpl_bk_mk', DB::table('pivot_cpl_bk_mk')->whereIn('id_cpl', $kur->cplProdi()->pluck('id'))->count()],
            ['pivot_mk_cpl',    DB::table('pivot_mk_cpl')->whereIn('id_mk', $kur->mataKuliah()->pluck('id'))->count()],
            ['CPMK',            Cpmk::where('id_kurikulum', $kur->id)->count()],
        ]);
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    private function seedMkPilihan(Kurikulum $kur): void
    {
        // Format kode MK: MK{nn} (2 digits)
        $existingCount = MataKuliah::where('id_kurikulum', $kur->id)->count();

        $mkPilihan = [
            // ── Manajemen TI ──────────────────────────────────────────────────
            ['MK52', 'Manajemen Risiko TI',                   2, 0, 6, 'Pilihan', 'Manajemen TI'],
            ['MK53', 'Manajemen Perubahan',                   2, 0, 6, 'Pilihan', 'Manajemen TI'],
            ['MK54', 'Pengukuran Kinerja TI',                 2, 0, 7, 'Pilihan', 'Manajemen TI'],
            ['MK55', 'Manajemen Keberlangsungan Bisnis',      2, 0, 8, 'Pilihan', 'Manajemen TI'],
            ['MK56', 'Audit Sistem Informasi',                2, 0, 8, 'Pilihan', 'Manajemen TI'],
            // ── Data Analytics ────────────────────────────────────────────────
            ['MK57', 'Sistem Pendukung Keputusan',            2, 0, 6, 'Pilihan', 'Data Analytics'],
            ['MK58', 'Visualisasi Informasi',                 2, 0, 6, 'Pilihan', 'Data Analytics'],
            ['MK59', 'Sistem Cerdas',                         2, 0, 7, 'Pilihan', 'Data Analytics'],
            ['MK60', 'Teknik Peramalan',                      2, 0, 8, 'Pilihan', 'Data Analytics'],
            ['MK61', 'Pengolahan Citra',                      2, 0, 8, 'Pilihan', 'Data Analytics'],
            // ── Bisnis Digital ────────────────────────────────────────────────
            ['MK62', 'Manajemen Merek Digital',               2, 0, 6, 'Pilihan', 'Bisnis Digital'],
            ['MK63', 'Pemasaran Digital',                     2, 0, 6, 'Pilihan', 'Bisnis Digital'],
            ['MK64', 'Kreatif Digital',                       2, 0, 7, 'Pilihan', 'Bisnis Digital'],
            ['MK65', 'Manajemen Hubungan Pelanggan',          2, 0, 8, 'Pilihan', 'Bisnis Digital'],
            ['MK66', 'Manajemen Rantai Pasok',                2, 0, 8, 'Pilihan', 'Bisnis Digital'],
        ];

        foreach ($mkPilihan as [$kode, $nama, $sksT, $sksP, $smt, $kat, $kons]) {
            MataKuliah::firstOrCreate(
                ['id_kurikulum' => $kur->id, 'kode_mk' => $kode],
                [
                    'nama_mk'      => $nama,
                    'sks_teori'    => $sksT,
                    'sks_praktikum'=> $sksP,
                    'semester'     => $smt,
                    'kategori_mk'  => $kat,
                    'konsentrasi'  => $kons,
                ]
            );
        }

        $newCount = MataKuliah::where('id_kurikulum', $kur->id)->count();
        $this->command->line("  MK: {$existingCount} → {$newCount} (ditambah " . ($newCount - $existingCount) . ")");
    }

    private function updatePivotPlCpl(Kurikulum $kur): void
    {
        // Dari Sheet 5 CPL-PL (BUKAN copy sheet):
        // Baris = CPL, Kolom = PL; "v" = terpetakan
        $mapping = [
            'CPL01' => ['PL01', 'PL02', 'PL03',              'PL05'],
            'CPL02' => ['PL01',         'PL03'                     ],
            'CPL03' => ['PL01'                                      ],
            'CPL04' => ['PL01'                                      ],
            'CPL05' => ['PL01', 'PL02', 'PL03', 'PL04', 'PL05'],
            'CPL06' => [        'PL02'                              ],
            'CPL07' => ['PL01'                                      ],
            'CPL08' => [                'PL03'                      ],
            'CPL09' => [        'PL02'                              ],
            'CPL10' => [                        'PL04'              ],
            'CPL11' => [                        'PL04'              ],
            'CPL12' => [                                  'PL05'],
            'CPL13' => [                                  'PL05'],
            'CPL14' => [                                  'PL05'],
        ];

        // Hapus semua mapping lama untuk kurikulum ini
        DB::table('pivot_pl_cpl')
            ->whereIn('id_pl', $kur->pl()->pluck('id'))
            ->delete();

        $plByKode  = $kur->pl()->get()->keyBy('kode_pl');
        $cplByKode = $kur->cplProdi()->get()->keyBy('kode_cpl');
        $inserts   = [];

        foreach ($mapping as $cplKode => $plKodes) {
            $cpl = $cplByKode[$cplKode] ?? null;
            if (!$cpl) continue;
            foreach ($plKodes as $plKode) {
                $pl = $plByKode[$plKode] ?? null;
                if (!$pl) continue;
                $inserts[] = ['id_cpl' => $cpl->id, 'id_pl' => $pl->id];
            }
        }

        DB::table('pivot_pl_cpl')->insert($inserts);
        $this->command->line("  pivot_pl_cpl: " . count($inserts) . " relasi");
    }

    private function updatePivotCplBk(Kurikulum $kur): void
    {
        // Dari Sheet 7 CPL-BK: BK → CPL yang terkait
        $mapping = [
            'BK01' => ['CPL01', 'CPL02', 'CPL05', 'CPL07', 'CPL09'],
            'BK02' => ['CPL02', 'CPL08'],
            'BK03' => ['CPL04'],
            'BK04' => ['CPL07'],
            'BK05' => ['CPL03'],
            'BK06' => ['CPL06'],
            'BK07' => ['CPL03'],
            'BK08' => ['CPL04'],
            'BK09' => ['CPL05', 'CPL10', 'CPL11'],
            'BK10' => ['CPL02', 'CPL07', 'CPL12', 'CPL13', 'CPL14'],
            'BK11' => ['CPL02', 'CPL08'],
            'BK12' => ['CPL08'],
            'BK13' => ['CPL05', 'CPL10', 'CPL11', 'CPL12', 'CPL13', 'CPL14'],
            'BK14' => ['CPL09'],
            'BK15' => ['CPL04', 'CPL06'],
            'BK16' => ['CPL03'],
            'BK17' => ['CPL09'],
            'BK18' => ['CPL08'],
            'BK19' => ['CPL03'],
            'BK20' => ['CPL03'],
            'BK21' => ['CPL03'],
        ];

        // Hapus mapping lama
        DB::table('pivot_cpl_bk')
            ->whereIn('id_cpl', $kur->cplProdi()->pluck('id'))
            ->delete();

        $bkByKode  = $kur->bahanKajian()->get()->keyBy('kode_bk');
        $cplByKode = $kur->cplProdi()->get()->keyBy('kode_cpl');
        $inserts   = [];

        foreach ($mapping as $bkKode => $cplKodes) {
            $bk = $bkByKode[$bkKode] ?? null;
            if (!$bk) continue;
            foreach ($cplKodes as $cplKode) {
                $cpl = $cplByKode[$cplKode] ?? null;
                if (!$cpl) continue;
                $inserts[] = ['id_cpl' => $cpl->id, 'id_bk' => $bk->id];
            }
        }

        DB::table('pivot_cpl_bk')->insert($inserts);
        $this->command->line("  pivot_cpl_bk: " . count($inserts) . " relasi");
    }

    private function updatePivotMkBk(Kurikulum $kur): void
    {
        // Dari Sheet 8 BK-MK: MK → BK yang terkait (semua 66 MK)
        $mapping = [
            'MK01' => ['BK09', 'BK13'],
            'MK02' => ['BK09', 'BK13'],
            'MK03' => ['BK13'],
            'MK04' => ['BK01'],
            'MK05' => ['BK07'],
            'MK06' => ['BK01', 'BK03'],
            'MK07' => ['BK11'],
            'MK08' => ['BK13'],
            'MK09' => ['BK13'],
            'MK10' => ['BK11'],
            'MK11' => ['BK02', 'BK11'],
            'MK12' => ['BK07'],
            'MK13' => ['BK03'],
            'MK14' => ['BK06'],
            'MK15' => ['BK14'],
            'MK16' => ['BK17'],
            'MK17' => ['BK06'],
            'MK18' => ['BK07', 'BK19'],
            'MK19' => ['BK05'],
            'MK20' => ['BK05', 'BK16'],
            'MK21' => ['BK02'],
            'MK22' => ['BK07', 'BK20'],
            'MK23' => ['BK04'],
            'MK24' => ['BK02', 'BK11', 'BK12'],
            'MK25' => ['BK14', 'BK15'],
            'MK26' => ['BK17'],
            'MK27' => ['BK03'],
            'MK28' => ['BK11'],
            'MK29' => ['BK07'],
            'MK30' => ['BK06'],
            'MK31' => ['BK08'],
            'MK32' => ['BK02', 'BK12', 'BK18'],
            'MK33' => ['BK06'],
            'MK34' => ['BK04', 'BK05', 'BK07', 'BK10', 'BK16', 'BK20'],
            'MK35' => ['BK11'],
            'MK36' => ['BK13'],
            'MK37' => ['BK06'],
            'MK38' => ['BK06'],
            'MK39' => ['BK09'],
            'MK40' => ['BK09', 'BK10'],
            'MK41' => ['BK10'],
            'MK42' => ['BK13'],
            'MK43' => ['BK09', 'BK10'],
            'MK44' => ['BK09', 'BK10'],
            'MK45' => ['BK09', 'BK10'],
            'MK46' => ['BK09'],
            'MK47' => ['BK07', 'BK21'],
            'MK48' => ['BK03', 'BK07', 'BK08'],
            'MK49' => ['BK07'],
            'MK50' => ['BK15'],
            'MK51' => ['BK03'],
            // 15 MK Pilihan baru:
            'MK52' => ['BK06'],
            'MK53' => ['BK06'],
            'MK54' => ['BK06'],
            'MK55' => ['BK06'],
            'MK56' => ['BK06'],
            'MK57' => ['BK07', 'BK12'],
            'MK58' => ['BK18'],
            'MK59' => ['BK07', 'BK12'],
            'MK60' => ['BK12'],
            'MK61' => ['BK12'],
            'MK62' => ['BK17'],
            'MK63' => ['BK17'],
            'MK64' => ['BK17'],
            'MK65' => ['BK14'],
            'MK66' => ['BK14'],
        ];

        // Hapus mapping lama
        DB::table('pivot_mk_bk')
            ->whereIn('id_mk', $kur->mataKuliah()->pluck('id'))
            ->delete();

        $mkByKode = $kur->mataKuliah()->get()->keyBy('kode_mk');
        $bkByKode = $kur->bahanKajian()->get()->keyBy('kode_bk');
        $inserts  = [];
        $missing  = [];

        foreach ($mapping as $mkKode => $bkKodes) {
            $mk = $mkByKode[$mkKode] ?? null;
            if (!$mk) { $missing[] = $mkKode; continue; }
            foreach ($bkKodes as $bkKode) {
                $bk = $bkByKode[$bkKode] ?? null;
                if (!$bk) continue;
                $inserts[] = ['id_mk' => $mk->id, 'id_bk' => $bk->id];
            }
        }

        foreach (array_chunk($inserts, 500) as $chunk) {
            DB::table('pivot_mk_bk')->insertOrIgnore($chunk);
        }

        $this->command->line("  pivot_mk_bk: " . count($inserts) . " relasi");
        if ($missing) {
            $this->command->warn("  MK tidak ditemukan: " . implode(', ', $missing));
        }
    }
}
