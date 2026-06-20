<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Berjalan PALING AWAL (nama file 0000_...).
 *
 * Mematikan pengecekan foreign key selama proses `migrate` berlangsung.
 * Beberapa migrasi membuat FK ke tabel yang baru dibuat/di-drop di migrasi
 * berikutnya (mis. arsip_rapat -> periode_kurikulum). SQLite mengizinkan ini,
 * tetapi MySQL menolaknya (error 1824). Dengan menonaktifkan pengecekan FK,
 * MySQL berperilaku seperti SQLite sehingga semua migrasi bisa berjalan, dan
 * skema akhirnya tetap konsisten.
 *
 * Sifatnya per-koneksi/sesi, jadi hanya berlaku selama proses migrate ini saja
 * dan tidak memengaruhi aplikasi yang sedang berjalan.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::enableForeignKeyConstraints();
    }
};
