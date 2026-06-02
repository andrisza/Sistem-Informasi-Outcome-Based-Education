<?php

namespace Database\Seeders;

use App\Models\Cpmk;
use App\Models\CplProdi;
use App\Models\KomponenAsesmen;
use App\Models\Kurikulum;
use App\Models\MataKuliah;
use App\Models\SemesterAkademik;
use App\Models\SubCpmk;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Generate CPMK + Sub-CPMK + Komponen Asesmen untuk seluruh MK
 * berdasarkan derivasi MK↔CPL (lewat BK) yang sudah ada di pivot_cpl_bk_mk.
 *
 * - 1 CPMK per pasangan (MK, CPL) yang ter-derive
 * - 2 Sub-CPMK per CPMK (bobot 50/50)
 * - 4 Komponen Asesmen template per MK: Quiz(10%) + Tugas(20%) + UTS(30%) + UAS(40%)
 * - pivot_cpl_cpmk diisi otomatis dari CPMK.id_cpl
 */
class CpmkDataSeeder extends Seeder
{
    public function seedFor(Kurikulum $kurikulum, ?SemesterAkademik $semester = null): void
    {
        $mkList  = $kurikulum->mataKuliah()->orderBy('semester')->orderBy('kode_mk')->get();
        $cplById = $kurikulum->cplProdi()->get()->keyBy('id');

        // Derivasi MK↔CPL dari pivot_cpl_bk_mk (jika kosong, fallback ke heuristik berdasarkan kategori MK)
        $derived = DB::table('pivot_cpl_bk_mk')
            ->whereIn('id_cpl', $cplById->keys())
            ->whereIn('id_mk',  $mkList->pluck('id'))
            ->select('id_mk', 'id_cpl')
            ->distinct()
            ->get()
            ->groupBy('id_mk')
            ->map(fn ($rows) => $rows->pluck('id_cpl')->unique()->values());

        // Fallback CPL kalau MK tidak punya derivasi (misal MKWK/MKDU yang umum)
        $fallbackCplByKategori = [
            'MKWK' => $cplById->where('kode_cpl', 'CPL10')->first()?->id,
            'MKDU' => $cplById->where('kode_cpl', 'CPL12')->first()?->id,
        ];

        foreach ($mkList as $mk) {
            $cpls = $derived->get($mk->id, collect());

            // Fallback untuk MK tanpa derivasi
            if ($cpls->isEmpty()) {
                $fallbackId = $fallbackCplByKategori[$mk->kategori_mk] ?? $cplById->where('kode_cpl', 'CPL12')->first()?->id;
                if ($fallbackId) $cpls = collect([$fallbackId]);
            }

            $this->seedCpmkForMk($kurikulum, $mk, $cpls->take(3), $cplById); // max 3 CPMK per MK
            $this->seedKomponenForMk($mk, $semester);
        }

        $this->syncPivotCplCpmk($kurikulum);
        $this->syncPivotMkCpl($kurikulum);
    }

    /**
     * Seed CPMK dan Sub-CPMK dengan format kode sesuai skema diagram OBE:
     *   CPMK{cpl_urutan_2digit}{cpmk_seq_1digit} → contoh: CPMK031
     *   Sub-CPMK{cpl_2digit}{cpmk_1digit}{sub_1digit} → contoh: Sub-CPMK0311
     */
    private function seedCpmkForMk(Kurikulum $k, MataKuliah $mk, $cplIds, $cplById): void
    {
        $seq = 1;
        foreach ($cplIds as $cplId) {
            if (!isset($cplById[$cplId])) continue;
            $cpl = $cplById[$cplId];

            // Format CPMK: CPMK{cpl_urutan_2digit}{cpmk_seq_1digit} → CPMK031
            $cplSeq   = str_pad($cpl->urutan, 2, '0', STR_PAD_LEFT);
            $kodeCpmk = 'CPMK' . $cplSeq . $seq;

            $deskCpmk = sprintf(
                'Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep %s yang terkait dengan capaian %s.',
                $mk->nama_mk,
                $cpl->kode_cpl
            );

            $cpmk = Cpmk::updateOrCreate(
                ['id_mk' => $mk->id, 'kode_cpmk' => $kodeCpmk],
                [
                    'id_kurikulum' => $k->id,
                    'id_cpl'       => $cpl->id,
                    'deskripsi'    => $deskCpmk,
                    'urutan'       => $seq,
                ]
            );

            // 2 Sub-CPMK per CPMK
            // Format Sub-CPMK: Sub-CPMK{cpl_2digit}{cpmk_1digit}{sub_1digit} → Sub-CPMK0311
            $subs = [
                [1, sprintf('Memahami konsep dasar dan teori %s.', $mk->nama_mk), 50.00],
                [2, sprintf('Menerapkan %s dalam studi kasus / praktik.', $mk->nama_mk), 50.00],
            ];
            foreach ($subs as [$subSeq, $subDesc, $subBobot]) {
                $kodeSub = 'Sub-CPMK' . $cplSeq . $seq . $subSeq;
                SubCpmk::updateOrCreate(
                    ['id_cpmk' => $cpmk->id, 'kode_sub_cpmk' => $kodeSub],
                    ['deskripsi' => $subDesc, 'bobot' => $subBobot, 'urutan' => $subSeq]
                );
            }

            $seq++;
        }
    }

    private function seedKomponenForMk(MataKuliah $mk, ?SemesterAkademik $semester): void
    {
        if (!$semester) return;

        // Pilih Sub-CPMK pertama & terakhir dari CPMK pertama MK ini sebagai referensi default
        $firstCpmk = Cpmk::where('id_mk', $mk->id)->orderBy('urutan')->first();
        if (!$firstCpmk) return;

        $subs = SubCpmk::where('id_cpmk', $firstCpmk->id)->orderBy('urutan')->get();
        if ($subs->isEmpty()) return;

        $subAwal  = $subs->first();
        $subAkhir = $subs->last();

        $komponen = [
            ['Quiz',           'Partisipasi', 10, 100, $subAwal->id],
            ['Tugas',          'Unjuk Kerja', 20, 100, $subAwal->id],
            ['UTS',            'UTS',         30, 100, $subAwal->id],
            ['UAS',            'UAS',         40, 100, $subAkhir->id],
        ];

        foreach ($komponen as [$nama, $jenis, $bobot, $skor, $subId]) {
            KomponenAsesmen::updateOrCreate(
                ['id_mk' => $mk->id, 'id_semester' => $semester->id, 'nama_komponen' => $nama],
                [
                    'id_sub_cpmk'    => $subId,
                    'jenis_komponen' => $jenis,
                    'bobot_persen'   => $bobot,
                    'skor_maks'      => $skor,
                ]
            );
        }
    }

    /**
     * Sinkronisasi pivot_mk_cpl dari CPMK yang sudah ada.
     */
    private function syncPivotMkCpl(Kurikulum $k): void
    {
        $cpmks = Cpmk::where('id_kurikulum', $k->id)->get();
        $rows = [];
        foreach ($cpmks as $c) {
            $rows[] = [
                'id_mk'   => $c->id_mk,
                'id_cpl'  => $c->id_cpl,
                'id_cpmk' => $c->id,
                'bobot'   => 1.00,
            ];
        }
        if (empty($rows)) return;
        // Delete existing then reinsert to avoid duplicate key issues
        $mkIds = $cpmks->pluck('id_mk')->unique()->values()->all();
        DB::table('pivot_mk_cpl')->whereIn('id_mk', $mkIds)->delete();
        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('pivot_mk_cpl')->insert($chunk);
        }
    }

    /**
     * Sinkronisasi pivot_cpl_cpmk: setiap CPMK terhubung dengan CPL induknya
     * (kolom id_cpl di tabel cpmk). Bobot default 1.00.
     */
    private function syncPivotCplCpmk(Kurikulum $k): void
    {
        $cpmks = Cpmk::where('id_kurikulum', $k->id)->get();
        $rows = $cpmks->map(fn ($c) => [
            'id_cpl'  => $c->id_cpl,
            'id_cpmk' => $c->id,
            'bobot'   => 1.00,
        ])->all();

        if (empty($rows)) return;

        DB::table('pivot_cpl_cpmk')->whereIn('id_cpmk', $cpmks->pluck('id'))->delete();
        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('pivot_cpl_cpmk')->insert($chunk);
        }
    }
}
