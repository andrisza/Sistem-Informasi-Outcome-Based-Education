<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Pastikan kategori bawaan untuk dropdown Data OBE tersedia di master_kategori
     * sehingga bisa ditambah/dinonaktifkan dari halaman Master Kategori.
     * Idempotent: insertOrIgnore mengandalkan unique (jenis, nama).
     */
    public function up(): void
    {
        $now = now();

        $defaults = [
            // CPL SN-Dikti → dropdown Kategori
            'cpl' => ['Sikap', 'Keterampilan Umum', 'Keterampilan Khusus', 'Pengetahuan'],
            // Bahan Kajian → dropdown Kompetensi
            'bk'  => ['Utama', 'Pendukung', 'Umum'],
        ];

        $rows = [];
        foreach ($defaults as $jenis => $names) {
            foreach ($names as $i => $nama) {
                $rows[] = [
                    'jenis'      => $jenis,
                    'nama'       => $nama,
                    'urutan'     => $i + 1,
                    'is_aktif'   => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        DB::table('master_kategori')->insertOrIgnore($rows);
    }

    public function down(): void
    {
        DB::table('master_kategori')
            ->whereIn('jenis', ['cpl', 'bk'])
            ->whereIn('nama', ['Sikap', 'Keterampilan Umum', 'Keterampilan Khusus', 'Pengetahuan', 'Utama', 'Pendukung', 'Umum'])
            ->delete();
    }
};
