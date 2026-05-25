<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_cpl', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_mahasiswa');
            $table->foreignId('id_cpl')->constrained('cpl_prodi')->cascadeOnDelete();
            $table->foreignId('id_kurikulum')->constrained('kurikulum')->cascadeOnDelete();
            $table->foreignId('id_semester')->constrained('semester_akademik')->cascadeOnDelete();
            $table->decimal('nilai_cpl', 6, 2);
            $table->decimal('skor_maks', 6, 2)->default(100.00);
            $table->decimal('total', 6, 2)->nullable();
            $table->tinyInteger('status_tercapai')->default(0);
            $table->timestamp('recalculated_at')->useCurrent()->useCurrentOnUpdate();

            $table->unique(['id_mahasiswa', 'id_cpl', 'id_semester'], 'uk_hk_cpl');
            $table->index(['id_kurikulum', 'id_cpl', 'id_semester'], 'idx_hk_cpl_kur');
            $table->foreign('id_mahasiswa')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_cpl');
    }
};
