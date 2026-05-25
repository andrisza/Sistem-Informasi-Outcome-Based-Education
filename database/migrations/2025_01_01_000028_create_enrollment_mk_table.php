<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollment_mk', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_mahasiswa');
            $table->foreignId('id_mk')->constrained('mata_kuliah')->cascadeOnDelete();
            $table->foreignId('id_semester')->constrained('semester_akademik')->cascadeOnDelete();
            $table->date('tanggal_daftar')->nullable();
            $table->enum('status', ['aktif', 'mengulang', 'lulus', 'tidak_lulus'])->default('aktif');

            $table->unique(['id_mahasiswa', 'id_mk', 'id_semester'], 'uk_enroll');
            $table->foreign('id_mahasiswa')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollment_mk');
    }
};
