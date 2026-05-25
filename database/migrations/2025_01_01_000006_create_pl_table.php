<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pl', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kurikulum')->constrained('kurikulum')->cascadeOnDelete();
            $table->string('kode_pl', 10);
            $table->text('deskripsi');
            $table->string('kategori', 100)->nullable();
            $table->string('ref_area_fungsi_1', 300)->nullable();
            $table->string('ref_area_fungsi_2', 300)->nullable();
            $table->string('ref_area_fungsi_3', 300)->nullable();
            $table->integer('urutan')->default(0);
            $table->softDeletes();

            $table->unique(['id_kurikulum', 'kode_pl'], 'uk_pl_kode');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pl');
    }
};
