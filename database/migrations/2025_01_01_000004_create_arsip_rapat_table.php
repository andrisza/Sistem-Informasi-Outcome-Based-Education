<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('arsip_rapat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kurikulum')->constrained('kurikulum')->cascadeOnDelete();
            $table->foreignId('id_periode')->nullable()->constrained('periode_kurikulum')->nullOnDelete();
            $table->string('judul_rapat', 255);
            $table->enum('jenis_rapat', [
                'pleno_kurikulum',
                'rapat_tim_kecil',
                'rapat_stakeholder',
                'rapat_evaluasi',
                'rapat_cqi',
                'rapat_lainnya',
            ]);
            $table->date('tanggal_rapat');
            $table->string('tempat', 200)->nullable();
            $table->text('agenda')->nullable();
            $table->longText('notulen')->nullable();
            $table->text('kesimpulan')->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->json('peserta')->nullable();
            $table->string('file_notulen', 500)->nullable();
            $table->string('file_daftar_hadir', 500)->nullable();
            $table->json('file_lampiran')->nullable();
            $table->unsignedBigInteger('dibuat_oleh');
            $table->timestamps();

            $table->index(['id_kurikulum', 'tanggal_rapat'], 'idx_rapat_kur');
            $table->foreign('dibuat_oleh')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arsip_rapat');
    }
};
