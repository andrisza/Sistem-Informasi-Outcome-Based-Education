<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // cpmk: tambah level_bloom
        Schema::table('cpmk', function (Blueprint $table) {
            $table->string('level_bloom', 10)->nullable()->after('urutan');
        });

        // mata_kuliah: tambah kode_prasyarat
        Schema::table('mata_kuliah', function (Blueprint $table) {
            $table->string('kode_prasyarat', 20)->nullable()->after('kategori_mk');
        });

        // bahan_kajian: tambah bidang_keilmuan
        Schema::table('bahan_kajian', function (Blueprint $table) {
            $table->string('bidang_keilmuan', 150)->nullable()->after('deskripsi');
        });

        // pl: tambah status, approved_by, approved_at
        Schema::table('pl', function (Blueprint $table) {
            $table->enum('status', ['draft', 'approved'])->default('draft')->after('urutan');
            $table->unsignedBigInteger('approved_by')->nullable()->after('status');
            $table->timestamp('approved_at')->nullable()->after('approved_by');

            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pl', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['status', 'approved_by', 'approved_at']);
        });

        Schema::table('bahan_kajian', function (Blueprint $table) {
            $table->dropColumn('bidang_keilmuan');
        });

        Schema::table('mata_kuliah', function (Blueprint $table) {
            $table->dropColumn('kode_prasyarat');
        });

        Schema::table('cpmk', function (Blueprint $table) {
            $table->dropColumn('level_bloom');
        });
    }
};
