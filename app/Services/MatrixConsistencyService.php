<?php

namespace App\Services;

use App\Models\Kurikulum;
use Illuminate\Support\Facades\DB;

/**
 * Menjaga konsistensi matriks turunan agar selalu sinkron dengan matriks primer.
 *
 * Matriks primer (manual): pivot_pl_cpl, pivot_cplsn_cplp, pivot_cpl_bk, pivot_mk_bk
 * Matriks turunan (derived, di-recompute saat primer berubah):
 *   - pivot_cpl_bk_mk  = pivot_cpl_bk ∩ pivot_mk_bk     (per BK)
 *   - pivot_mk_cpl     = projection dari pivot_cpl_bk_mk (MK ↔ CPL via BK)
 *     CPMK linker dipakai apa adanya bila ada; jika tidak ada CPMK untuk pair tsb,
 *     baris pivot_mk_cpl untuk pair itu dilewati (butuh CPMK eksplisit).
 */
class MatrixConsistencyService
{
    /**
     * Hitung ulang seluruh matriks turunan untuk satu kurikulum.
     */
    public function syncAll(Kurikulum $kurikulum): void
    {
        DB::transaction(function () use ($kurikulum) {
            $this->syncCplBkMk($kurikulum);
            $this->syncMkCpl($kurikulum);
        });
    }

    /**
     * pivot_cpl_bk_mk = {(cpl, bk, mk) : (cpl,bk) ∈ pivot_cpl_bk AND (mk,bk) ∈ pivot_mk_bk}
     *
     * @param bool $additive  true  → hanya menambah baris turunan baru (manual edit user dilestarikan).
     *                        false → rebuild total (hapus dulu, isi ulang).
     */
    public function syncCplBkMk(Kurikulum $kurikulum, bool $additive = false): void
    {
        $cplIds = $kurikulum->cplProdi()->pluck('id');
        $bkIds  = $kurikulum->bahanKajian()->pluck('id');
        $mkIds  = $kurikulum->mataKuliah()->pluck('id');

        if (!$additive) {
            DB::table('pivot_cpl_bk_mk')->whereIn('id_cpl', $cplIds)->delete();
        }

        if ($cplIds->isEmpty() || $bkIds->isEmpty() || $mkIds->isEmpty()) {
            return;
        }

        $cplBk = DB::table('pivot_cpl_bk')
            ->whereIn('id_cpl', $cplIds)
            ->whereIn('id_bk', $bkIds)
            ->get();

        $mkBk = DB::table('pivot_mk_bk')
            ->whereIn('id_mk', $mkIds)
            ->whereIn('id_bk', $bkIds)
            ->get()
            ->groupBy('id_bk');

        $rows = [];
        foreach ($cplBk as $cb) {
            $mksForBk = $mkBk->get($cb->id_bk, collect());
            foreach ($mksForBk as $mb) {
                $rows[] = [
                    'id_cpl' => $cb->id_cpl,
                    'id_bk'  => $cb->id_bk,
                    'id_mk'  => $mb->id_mk,
                ];
            }
        }

        // INSERT IGNORE supaya tidak konflik dengan baris manual yang sudah ada
        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('pivot_cpl_bk_mk')->insertOrIgnore($chunk);
        }
    }

    /**
     * pivot_mk_cpl = projection (mk, cpl) dari pivot_cpl_bk_mk dengan CPMK terkait
     * (pair MK↔CPL yang belum punya CPMK akan dilewati; user perlu buat CPMK).
     */
    public function syncMkCpl(Kurikulum $kurikulum): void
    {
        $cplIds = $kurikulum->cplProdi()->pluck('id');
        $mkIds  = $kurikulum->mataKuliah()->pluck('id');

        DB::table('pivot_mk_cpl')->whereIn('id_mk', $mkIds)->delete();

        if ($cplIds->isEmpty() || $mkIds->isEmpty()) {
            return;
        }

        // Pasangan (mk, cpl) unik dari pivot_cpl_bk_mk
        $pairs = DB::table('pivot_cpl_bk_mk')
            ->whereIn('id_cpl', $cplIds)
            ->whereIn('id_mk',  $mkIds)
            ->select('id_mk', 'id_cpl')
            ->distinct()
            ->get();

        // Untuk tiap (mk,cpl) cari CPMK yang sudah ada (id_mk, id_cpl) di tabel cpmk
        $cpmk = DB::table('cpmk')
            ->whereIn('id_mk', $mkIds)
            ->whereIn('id_cpl', $cplIds)
            ->select('id_mk', 'id_cpl', 'id')
            ->get()
            ->groupBy(fn ($r) => $r->id_mk . '-' . $r->id_cpl);

        $rows = [];
        foreach ($pairs as $p) {
            $key  = $p->id_mk . '-' . $p->id_cpl;
            $list = $cpmk->get($key);
            if (!$list) {
                // Belum ada CPMK eksplisit; lewati. UI bisa menampilkan placeholder.
                continue;
            }
            foreach ($list as $c) {
                $rows[] = [
                    'id_mk'   => $p->id_mk,
                    'id_cpl'  => $p->id_cpl,
                    'id_cpmk' => $c->id,
                    'bobot'   => 1.00,
                ];
            }
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('pivot_mk_cpl')->insert($chunk);
        }
    }
}
