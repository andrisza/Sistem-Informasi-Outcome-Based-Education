<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nilai_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_mahasiswa');
            $table->foreignId('id_komponen')->constrained('komponen_asesmen')->cascadeOnDelete();
            $table->foreignId('id_semester')->constrained('semester_akademik')->cascadeOnDelete();
            $table->decimal('nilai_mentah', 6, 2);
            $table->timestamps();

            $table->unique(['id_mahasiswa', 'id_komponen', 'id_semester'], 'uk_nilai');
            $table->index(['id_mahasiswa', 'id_semester'], 'idx_nilai_mhs_smt');
            $table->index(['id_komponen', 'id_semester'], 'idx_nilai_komponen');
            $table->foreign('id_mahasiswa')->references('id')->on('users')->cascadeOnDelete();
        });

        DB::statement('ALTER TABLE nilai_mahasiswa ADD CONSTRAINT chk_nilai CHECK (nilai_mentah BETWEEN 0 AND 100)');
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai_mahasiswa');
    }
};
