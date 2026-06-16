<?php

namespace App\Services;

use App\Models\BatasKetercapaian;
use App\Models\Cpmk;
use App\Models\EnrollmentMk;
use App\Models\HasilCpl;
use App\Models\HasilCpmk;
use App\Models\HasilPl;
use App\Models\HasilSubCpmk;
use App\Models\KomponenAsesmen;
use App\Models\MataKuliah;
use App\Models\NilaiMahasiswa;
use App\Models\SemesterAkademik;
use App\Models\SubCpmk;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Menghitung ulang rantai hasil OBE saat nilai mahasiswa berubah.
 *
 * Rantai kalkulasi:
 *   nilai_mahasiswa (per komponen)
 *     → hasil_sub_cpmk  = rata-rata berbobot nilai komponen yang terhubung ke Sub-CPMK
 *     → hasil_cpmk      = Σ (hasil_sub_cpmk × sub_cpmk.bobot / Σbobot)
 *     → hasil_cpl       = rata-rata hasil_cpmk semua CPMK yang menarget CPL tersebut
 *
 * Dipanggil dari NilaiController::store() setelah batch nilai disimpan.
 */
class GradeCalculationService
{
    /**
     * Entry point: hitung ulang seluruh rantai untuk satu MK × Semester.
     */
    public function recalcForMk(MataKuliah $mk, SemesterAkademik $semester): void
    {
        // Mahasiswa aktif yang terdaftar di MK ini
        $mahasiswaIds = EnrollmentMk::where('id_mk', $mk->id)
            ->where('id_semester', $semester->id)
            ->where('status', 'aktif')
            ->pluck('id_mahasiswa');

        if ($mahasiswaIds->isEmpty()) {
            return;
        }

        // Komponen asesmen yang terhubung ke Sub-CPMK
        $komponen = KomponenAsesmen::where('id_mk', $mk->id)
            ->where('id_semester', $semester->id)
            ->whereNotNull('id_sub_cpmk')
            ->get();

        if ($komponen->isEmpty()) {
            return;
        }

        // Nilai seluruh mahasiswa untuk komponen-komponen ini
        // Struktur: [id_mahasiswa => [id_komponen => NilaiMahasiswa]]
        $allNilai = NilaiMahasiswa::whereIn('id_mahasiswa', $mahasiswaIds)
            ->whereIn('id_komponen', $komponen->pluck('id'))
            ->where('id_semester', $semester->id)
            ->get()
            ->groupBy('id_mahasiswa')
            ->map(fn (Collection $rows) => $rows->keyBy('id_komponen'));

        // Komponen dikelompokkan per Sub-CPMK: [id_sub_cpmk => Collection<KomponenAsesmen>]
        $komponenPerSubCpmk = $komponen->groupBy('id_sub_cpmk');

        // Sub-CPMK → CPMK chain
        $subCpmkIds  = $komponen->pluck('id_sub_cpmk')->unique();
        $subCpmkList = SubCpmk::whereIn('id', $subCpmkIds)->get()->keyBy('id');

        $cpmkIds  = $subCpmkList->pluck('id_cpmk')->unique();
        // Eager-load subCpmk agar tidak N+1 di inner loop
        $cpmkList = Cpmk::whereIn('id', $cpmkIds)
            ->with('subCpmk')
            ->get()
            ->keyBy('id');

        DB::transaction(function () use (
            $mahasiswaIds, $mk, $semester,
            $allNilai, $komponenPerSubCpmk, $subCpmkList, $cpmkList
        ) {
            foreach ($mahasiswaIds as $mhsId) {
                $nilaiMhs = $allNilai->get($mhsId, collect());

                // ── 1. hasil_sub_cpmk ──────────────────────────────────────────
                // Rata-rata berbobot dari komponen yang terhubung ke sub_cpmk ini.
                // Bobot = bobot_persen komponen (Tugas 30, UTS 30, UAS 40, dll.)
                $hasilSubCpmk = collect(); // [id_sub_cpmk => nilai]

                foreach ($komponenPerSubCpmk as $subCpmkId => $komps) {
                    $totalBobot = 0.0;
                    $totalNilai = 0.0;

                    foreach ($komps as $k) {
                        $nilaiRow = $nilaiMhs->get($k->id);
                        if ($nilaiRow === null) {
                            continue;
                        }
                        $bobot       = (float) $k->bobot_persen;
                        $totalNilai += (float) $nilaiRow->nilai_mentah * $bobot;
                        $totalBobot += $bobot;
                    }

                    if ($totalBobot === 0.0) {
                        continue;
                    }

                    $nilaiSubCpmk = round($totalNilai / $totalBobot, 2);
                    $hasilSubCpmk->put($subCpmkId, $nilaiSubCpmk);

                    HasilSubCpmk::updateOrCreate(
                        ['id_mahasiswa' => $mhsId, 'id_sub_cpmk' => $subCpmkId, 'id_semester' => $semester->id],
                        ['nilai'        => $nilaiSubCpmk]
                    );
                }

                // ── 2. hasil_cpmk ──────────────────────────────────────────────
                // Σ (hasil_sub_cpmk × sub_cpmk.bobot) / Σ(sub_cpmk.bobot)
                $hasilCpmk = collect(); // [id_cpmk => nilai]

                foreach ($cpmkList as $cpmkId => $cpmk) {
                    $totalBobot = 0.0;
                    $nilaiCpmk  = 0.0;

                    foreach ($cpmk->subCpmk as $sub) {
                        if (!$hasilSubCpmk->has($sub->id)) {
                            continue;
                        }
                        $bobot      = (float) $sub->bobot;
                        $nilaiCpmk += $hasilSubCpmk->get($sub->id) * $bobot;
                        $totalBobot += $bobot;
                    }

                    if ($totalBobot === 0.0) {
                        continue;
                    }

                    $nilaiCpmk = round($nilaiCpmk / $totalBobot, 2);
                    $hasilCpmk->put($cpmkId, $nilaiCpmk);

                    HasilCpmk::updateOrCreate(
                        ['id_mahasiswa' => $mhsId, 'id_cpmk' => $cpmkId, 'id_semester' => $semester->id],
                        ['nilai'        => $nilaiCpmk]
                    );
                }

                // ── 3. hasil_cpl ───────────────────────────────────────────────
                // Rata-rata hasil_cpmk dari seluruh CPMK yang menarget CPL tersebut
                // di kurikulum ini pada semester ini.
                $cplIds = $cpmkList->pluck('id_cpl')->unique()->filter();

                foreach ($cplIds as $cplId) {
                    $this->recalcCplForMahasiswa(
                        $mhsId,
                        (int) $cplId,
                        $mk->id_kurikulum,
                        $semester->id
                    );
                }
            }
        });
    }

    /**
     * Hitung ulang hasil_cpl untuk satu mahasiswa × CPL × kurikulum × semester.
     *
     * Formula panduan OBE (Tabel K):
     *   Capaian CPL = rata-rata Nilai MK dari semua MK yang berkontribusi ke CPL ini
     *   Nilai MK    = Σ(hasil_cpmk × bobot_cpmk) / Σ(bobot_cpmk)   [rata-rata berbobot]
     *
     * Jika bobot komponen asesmen belum tersedia, fallback ke rata-rata sederhana.
     */
    public function recalcCplForMahasiswa(
        int $mhsId,
        int $cplId,
        int $kurikulumId,
        int $semesterId
    ): void {
        // CPMK yang menarget CPL ini, dengan id_mk untuk grouping per MK
        $cpmkList = Cpmk::where('id_kurikulum', $kurikulumId)
            ->where('id_cpl', $cplId)
            ->select('id', 'id_mk')
            ->get();

        if ($cpmkList->isEmpty()) {
            return;
        }

        $cpmkByMk   = $cpmkList->groupBy('id_mk');
        $nilaiPerMk = [];

        foreach ($cpmkByMk as $mkId => $cpmkForMk) {
            $cpmkIds = $cpmkForMk->pluck('id');

            $hasilForMk = HasilCpmk::whereIn('id_cpmk', $cpmkIds)
                ->where('id_mahasiswa', $mhsId)
                ->where('id_semester', $semesterId)
                ->pluck('nilai', 'id_cpmk');

            if ($hasilForMk->isEmpty()) {
                continue;
            }

            // Bobot tiap CPMK dari komponen_asesmen → sub_cpmk → cpmk
            $bobotRows = DB::table('komponen_asesmen as ka')
                ->join('sub_cpmk as sc', 'sc.id', '=', 'ka.id_sub_cpmk')
                ->where('ka.id_mk', $mkId)
                ->where('ka.id_semester', $semesterId)
                ->whereNotNull('ka.id_sub_cpmk')
                ->whereIn('sc.id_cpmk', $cpmkIds)
                ->select('sc.id_cpmk', DB::raw('SUM(ka.bobot_persen) as total_bobot'))
                ->groupBy('sc.id_cpmk')
                ->get()
                ->keyBy('id_cpmk');

            $totalBobot = (float) $bobotRows->sum('total_bobot');

            if ($totalBobot > 0) {
                $weighted = 0.0;
                foreach ($hasilForMk as $cpmkId => $nilai) {
                    $b = (float) ($bobotRows->get($cpmkId)?->total_bobot ?? 0);
                    $weighted += (float) $nilai * $b;
                }
                $nilaiPerMk[] = round($weighted / $totalBobot, 2);
            } else {
                $nilaiPerMk[] = round((float) $hasilForMk->avg(), 2);
            }
        }

        if (empty($nilaiPerMk)) {
            return;
        }

        $nilaiCpl = round(array_sum($nilaiPerMk) / count($nilaiPerMk), 2);
        $batas    = BatasKetercapaian::where('id_cpl', $cplId)
            ->where('id_kurikulum', $kurikulumId)
            ->value('batas_nilai') ?? 70.0;
        $tercapai = $nilaiCpl >= (float) $batas ? 1 : 0;

        HasilCpl::updateOrCreate(
            ['id_mahasiswa' => $mhsId, 'id_cpl' => $cplId, 'id_semester' => $semesterId],
            [
                'id_kurikulum'    => $kurikulumId,
                'nilai_cpl'       => $nilaiCpl,
                'skor_maks'       => 100.00,
                'status_tercapai' => $tercapai,
            ]
        );

        $this->recalcPlForMahasiswa($mhsId, $cplId, $kurikulumId, $semesterId);
    }

    /**
     * Hitung ulang hasil_pl untuk semua PL yang terhubung ke CPL ini.
     */
    private function recalcPlForMahasiswa(
        int $mhsId,
        int $cplId,
        int $kurikulumId,
        int $semesterId
    ): void {
        // PL yang terhubung ke CPL ini
        $plIds = DB::table('pivot_pl_cpl')
            ->where('id_cpl', $cplId)
            ->pluck('id_pl');

        if ($plIds->isEmpty()) {
            return;
        }

        foreach ($plIds as $plId) {
            // Semua CPL yang terhubung ke PL ini (via pivot_pl_cpl)
            $cplIdsForPl = DB::table('pivot_pl_cpl')
                ->where('id_pl', $plId)
                ->pluck('id_cpl');

            // Rata-rata hasil_cpl untuk semua CPL yang terhubung ke PL ini
            $nilaiPerCpl = HasilCpl::whereIn('id_cpl', $cplIdsForPl)
                ->where('id_mahasiswa', $mhsId)
                ->where('id_kurikulum', $kurikulumId)
                ->where('id_semester', $semesterId)
                ->pluck('nilai_cpl');

            if ($nilaiPerCpl->isEmpty()) {
                continue;
            }

            $nilaiPl = round((float) $nilaiPerCpl->avg(), 2);

            HasilPl::updateOrCreate(
                ['id_mahasiswa' => $mhsId, 'id_pl' => $plId, 'id_semester' => $semesterId],
                [
                    'id_kurikulum'    => $kurikulumId,
                    'nilai_pl'        => $nilaiPl,
                    // PL dianggap tercapai jika rata-rata CPL-nya >= 70
                    'status_tercapai' => $nilaiPl >= 70.0 ? 1 : 0,
                ]
            );
        }
    }
}
