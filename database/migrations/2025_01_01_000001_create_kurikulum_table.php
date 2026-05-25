<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kurikulum', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique('uk_kurikulum_kode');
            $table->string('nama_kurikulum', 150);
            $table->string('program_studi', 150);
            $table->enum('jenjang', ['D3', 'D4', 'S1', 'S2', 'S3'])->default('S1');
            $table->year('tahun_mulai');
            $table->year('tahun_selesai');
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->text('tujuan')->nullable();
            $table->text('sasaran')->nullable();
            $table->enum('status', ['draft', 'aktif', 'arsip'])->default('draft');
            $table->timestamp('locked_at')->nullable();
            // locked_by, dibuat_oleh, disahkan_oleh FKs ke users ditambahkan di migration terakhir
            $table->unsignedBigInteger('locked_by')->nullable();
            $table->unsignedBigInteger('dibuat_oleh')->nullable();
            $table->unsignedBigInteger('disahkan_oleh')->nullable();
            $table->date('disahkan_pada')->nullable();
            $table->timestamps();

            $table->index('status', 'idx_kur_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kurikulum');
    }
};
