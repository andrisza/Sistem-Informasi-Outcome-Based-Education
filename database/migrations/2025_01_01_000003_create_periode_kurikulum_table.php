<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periode_kurikulum', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kurikulum')->constrained('kurikulum')->cascadeOnDelete();
            $table->string('nama_periode', 100);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->unsignedBigInteger('ketua_tim')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('dokumen_sk', 500)->nullable();
            $table->enum('status', ['perencanaan', 'berjalan', 'selesai'])->default('perencanaan');
            $table->timestamps();

            $table->foreign('ketua_tim')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periode_kurikulum');
    }
};
