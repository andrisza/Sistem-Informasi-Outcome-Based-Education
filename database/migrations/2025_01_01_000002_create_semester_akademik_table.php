<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('semester_akademik', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 30)->unique('uk_semester_nama');
            $table->string('tahun_akademik', 10);
            $table->enum('jenis', ['Ganjil', 'Genap', 'Pendek']);
            $table->tinyInteger('is_aktif')->default(0);
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->index('is_aktif', 'idx_semester_aktif');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('semester_akademik');
    }
};
