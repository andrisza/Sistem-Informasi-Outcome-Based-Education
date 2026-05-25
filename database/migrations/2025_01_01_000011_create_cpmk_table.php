<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cpmk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kurikulum')->constrained('kurikulum')->cascadeOnDelete();
            $table->foreignId('id_mk')->constrained('mata_kuliah')->cascadeOnDelete();
            $table->foreignId('id_cpl')->constrained('cpl_prodi')->cascadeOnDelete();
            $table->string('kode_cpmk', 20);
            $table->text('deskripsi');
            $table->integer('urutan')->default(0);
            $table->softDeletes();

            $table->unique(['id_mk', 'kode_cpmk'], 'uk_cpmk_kode_mk');
            $table->index('id_cpl', 'idx_cpmk_cpl');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cpmk');
    }
};
