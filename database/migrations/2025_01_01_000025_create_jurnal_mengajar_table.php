<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jurnal_mengajar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_rps_pertemuan')->constrained('rps_pertemuan')->cascadeOnDelete();
            $table->unsignedBigInteger('id_dosen');
            $table->date('tanggal_pelaksanaan');
            $table->text('realisasi_materi');
            $table->integer('jumlah_hadir')->nullable();
            $table->string('file_bukti_path', 500)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->foreign('id_dosen')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jurnal_mengajar');
    }
};
