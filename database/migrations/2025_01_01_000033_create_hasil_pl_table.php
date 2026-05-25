<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_pl', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_mahasiswa');
            $table->foreignId('id_pl')->constrained('pl')->cascadeOnDelete();
            $table->foreignId('id_kurikulum')->constrained('kurikulum')->cascadeOnDelete();
            $table->foreignId('id_semester')->constrained('semester_akademik')->cascadeOnDelete();
            $table->decimal('nilai_pl', 6, 2);
            $table->tinyInteger('status_tercapai')->default(0);
            $table->timestamp('recalculated_at')->useCurrent()->useCurrentOnUpdate();

            $table->unique(['id_mahasiswa', 'id_pl', 'id_semester'], 'uk_hk_pl');
            $table->foreign('id_mahasiswa')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_pl');
    }
};
