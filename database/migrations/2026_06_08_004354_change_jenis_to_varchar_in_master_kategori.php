<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah kolom jenis dari ENUM ke VARCHAR agar bisa menerima jenis kustom
        \DB::statement("ALTER TABLE master_kategori MODIFY jenis VARCHAR(50) NOT NULL");
    }

    public function down(): void
    {
        // Rollback: pastikan tidak ada data di luar enum sebelum down
        \DB::statement("ALTER TABLE master_kategori MODIFY jenis ENUM('pl','cpl','bk','mk') NOT NULL");
    }
};
