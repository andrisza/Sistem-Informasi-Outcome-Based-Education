<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bahan_kajian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kurikulum')->constrained('kurikulum')->cascadeOnDelete();
            $table->string('kode_bk', 10);
            $table->string('nama_bk', 200);
            $table->text('deskripsi')->nullable();
            $table->integer('urutan')->default(0);
            $table->softDeletes();

            $table->unique(['id_kurikulum', 'kode_bk'], 'uk_bk_kode');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bahan_kajian');
    }
};
