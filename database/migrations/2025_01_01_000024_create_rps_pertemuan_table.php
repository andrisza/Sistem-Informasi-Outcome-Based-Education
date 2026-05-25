<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rps_pertemuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_rps')->constrained('rps_header')->cascadeOnDelete();
            $table->integer('minggu_ke');
            $table->text('materi_pembelajaran');
            $table->string('metode_pembelajaran', 300)->nullable();
            $table->foreignId('id_sub_cpmk')->constrained('sub_cpmk')->cascadeOnDelete();
            $table->text('indikator_penilaian')->nullable();
            $table->string('estimasi_waktu', 50)->nullable();
            $table->string('media_pembelajaran', 200)->nullable();
            $table->text('referensi')->nullable();

            $table->unique(['id_rps', 'minggu_ke'], 'uk_rps_minggu');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rps_pertemuan');
    }
};
