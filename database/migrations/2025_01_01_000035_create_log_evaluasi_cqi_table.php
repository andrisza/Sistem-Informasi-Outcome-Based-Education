<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_evaluasi_cqi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_mk')->constrained('mata_kuliah')->cascadeOnDelete();
            $table->foreignId('id_kurikulum')->constrained('kurikulum')->cascadeOnDelete();
            $table->foreignId('id_semester')->constrained('semester_akademik')->cascadeOnDelete();
            $table->foreignId('id_cpl_terdampak')->nullable()->constrained('cpl_prodi')->nullOnDelete();
            $table->text('temuan');
            $table->text('akar_masalah')->nullable();
            $table->text('rekomendasi');
            $table->string('target_implementasi', 30)->nullable();
            $table->enum('status', ['belum', 'proses', 'selesai'])->default('belum');
            $table->unsignedBigInteger('dilaporkan_oleh');
            $table->unsignedBigInteger('disetujui_oleh')->nullable();
            $table->timestamps();

            $table->foreign('dilaporkan_oleh')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('disetujui_oleh')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_evaluasi_cqi');
    }
};
