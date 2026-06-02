<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── notifikasi ─────────────────────────────────────────────────────────
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->string('judul', 200);
            $table->text('pesan');
            $table->string('url', 500)->nullable();
            $table->string('tipe', 50)->default('info'); // info, success, warning, review
            $table->tinyInteger('dibaca')->default(0);
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('id_user')->references('id')->on('users')->cascadeOnDelete();
        });

        // ── komentar_review ────────────────────────────────────────────────────
        Schema::create('komentar_review', function (Blueprint $table) {
            $table->id();
            $table->string('model_type', 150);
            $table->unsignedBigInteger('model_id');
            $table->unsignedBigInteger('id_user');
            $table->text('konten');
            $table->string('elemen', 200)->nullable();
            $table->enum('status', ['open', 'resolved'])->default('open');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('id_user')->references('id')->on('users')->cascadeOnDelete();
            $table->index(['model_type', 'model_id']);
        });

        // ── lesson_learned ─────────────────────────────────────────────────────
        Schema::create('lesson_learned', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_kurikulum');
            $table->string('kategori', 100);
            $table->text('temuan');
            $table->text('rekomendasi');
            $table->enum('prioritas', ['high', 'medium', 'low'])->default('medium');
            $table->enum('status', ['open', 'ditangani'])->default('open');
            $table->unsignedBigInteger('id_user');
            $table->timestamps();

            $table->foreign('id_kurikulum')->references('id')->on('kurikulum')->cascadeOnDelete();
            $table->foreign('id_user')->references('id')->on('users')->cascadeOnDelete();
        });

        // ── checklist_ledik ────────────────────────────────────────────────────
        Schema::create('checklist_ledik', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_kurikulum');
            $table->enum('elemen', ['PLAN', 'DO', 'CHECK', 'ACTION']);
            $table->string('kode_indikator', 20);
            $table->text('deskripsi_indikator');
            $table->enum('status', ['ya', 'tidak', 'parsial'])->nullable();
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->unique(['id_kurikulum', 'kode_indikator']);
            $table->foreign('id_kurikulum')->references('id')->on('kurikulum')->cascadeOnDelete();
        });

        // ── log_distribusi ─────────────────────────────────────────────────────
        Schema::create('log_distribusi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_kurikulum');
            $table->unsignedBigInteger('id_pengirim');
            $table->unsignedBigInteger('id_penerima');
            $table->string('nama_dokumen', 255);
            $table->string('versi', 50)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('id_kurikulum')->references('id')->on('kurikulum')->cascadeOnDelete();
            $table->foreign('id_pengirim')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('id_penerima')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_distribusi');
        Schema::dropIfExists('checklist_ledik');
        Schema::dropIfExists('lesson_learned');
        Schema::dropIfExists('komentar_review');
        Schema::dropIfExists('notifikasi');
    }
};
