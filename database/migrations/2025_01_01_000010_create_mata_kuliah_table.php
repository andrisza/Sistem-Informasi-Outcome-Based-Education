<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mata_kuliah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kurikulum')->constrained('kurikulum')->cascadeOnDelete();
            $table->string('kode_mk', 20);
            $table->string('nama_mk', 200);
            $table->integer('sks_teori')->default(0);
            $table->integer('sks_praktikum')->default(0);
            // Kolom komputasi: sks_total = sks_teori + sks_praktikum
            $table->integer('sks_total')->storedAs('sks_teori + sks_praktikum');
            $table->integer('semester');
            $table->enum('kategori_mk', ['Wajib', 'Pilihan', 'MKWK', 'MKDU']);
            $table->softDeletes();

            $table->unique(['id_kurikulum', 'kode_mk'], 'uk_mk_kode');
            $table->index(['id_kurikulum', 'semester'], 'idx_mk_semester');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mata_kuliah');
    }
};
