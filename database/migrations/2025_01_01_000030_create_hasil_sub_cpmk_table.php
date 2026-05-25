<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_sub_cpmk', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_mahasiswa');
            $table->foreignId('id_sub_cpmk')->constrained('sub_cpmk')->cascadeOnDelete();
            $table->foreignId('id_semester')->constrained('semester_akademik')->cascadeOnDelete();
            $table->decimal('nilai', 6, 2);
            $table->timestamp('recalculated_at')->useCurrent()->useCurrentOnUpdate();

            $table->unique(['id_mahasiswa', 'id_sub_cpmk', 'id_semester'], 'uk_hk_sc');
            $table->foreign('id_mahasiswa')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_sub_cpmk');
    }
};
