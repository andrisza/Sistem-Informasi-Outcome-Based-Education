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

    }

    public function down(): void
    {
        Schema::dropIfExists('komentar_review');
        Schema::dropIfExists('notifikasi');
    }
};
