<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cpmk_penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_cpmk')->constrained('cpmk')->cascadeOnDelete();
            $table->string('tahap_penilaian', 60)->default('Awal-Tengah Semester');
            // Teknik penilaian (boolean checkboxes)
            $table->boolean('teknik_quiz')->default(false);
            $table->boolean('teknik_observasi')->default(false);
            $table->boolean('teknik_unjuk_kerja')->default(false);
            $table->boolean('teknik_uts')->default(false);
            $table->boolean('teknik_uas')->default(false);
            $table->boolean('teknik_tes_lisan')->default(false);
            // Bobot penilaian (numeric weights, sum should = 100)
            $table->decimal('bobot_quiz', 5, 2)->nullable();
            $table->decimal('bobot_observasi', 5, 2)->nullable();
            $table->decimal('bobot_unjuk_kerja', 5, 2)->nullable();
            $table->decimal('bobot_uts', 5, 2)->nullable();
            $table->decimal('bobot_uas', 5, 2)->nullable();
            $table->decimal('bobot_tes_lisan', 5, 2)->nullable();
            // Instrumen & Kriteria
            $table->text('instrumen')->nullable();
            $table->text('kriteria')->nullable();
            $table->unique('id_cpmk', 'uk_cpmk_penilaian');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cpmk_penilaian');
    }
};
