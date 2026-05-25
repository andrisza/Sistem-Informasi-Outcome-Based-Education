<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('distribusi_semester', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kurikulum')->constrained('kurikulum')->cascadeOnDelete();
            $table->integer('semester');
            $table->integer('total_sks')->default(0);
            $table->integer('jumlah_mk')->default(0);
            $table->text('keterangan')->nullable();

            $table->unique(['id_kurikulum', 'semester'], 'uk_dist_kur_smt');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distribusi_semester');
    }
};
