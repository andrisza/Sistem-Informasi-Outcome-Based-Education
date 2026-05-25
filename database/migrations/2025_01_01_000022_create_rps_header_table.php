<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rps_header', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_mk')->constrained('mata_kuliah')->cascadeOnDelete();
            $table->foreignId('id_semester')->constrained('semester_akademik')->cascadeOnDelete();
            $table->unsignedBigInteger('id_dosen_pengembang');
            $table->unsignedBigInteger('id_koordinator_bk')->nullable();
            $table->unsignedBigInteger('id_kaprodi_pengesah')->nullable();
            $table->date('tanggal_penyusunan');
            $table->string('kode_dokumen', 50)->nullable();
            $table->enum('status', ['draft', 'review', 'disahkan'])->default('draft');
            $table->timestamp('disahkan_pada')->nullable();
            $table->timestamps();

            $table->unique(['id_mk', 'id_semester'], 'uk_rps_mk_smt');
            $table->foreign('id_dosen_pengembang')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('id_koordinator_bk')->references('id')->on('users')->nullOnDelete();
            $table->foreign('id_kaprodi_pengesah')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rps_header');
    }
};
