<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bahan_kajian', function (Blueprint $table) {
            $table->enum('kompetensi', ['Utama', 'Pendukung', 'Umum'])->nullable()->after('deskripsi');
            $table->string('referensi', 150)->nullable()->after('kompetensi');
        });

        Schema::table('cpl_prodi', function (Blueprint $table) {
            $table->text('referensi')->nullable()->after('kategori');
        });

        Schema::table('pl', function (Blueprint $table) {
            $table->text('referensi')->nullable()->after('kategori');
        });

        Schema::table('mata_kuliah', function (Blueprint $table) {
            $table->enum('kompetensi_mk', ['Utama', 'Pendukung'])->default('Utama')->after('kategori_mk');
        });
    }

    public function down(): void
    {
        Schema::table('bahan_kajian', function (Blueprint $table) {
            $table->dropColumn(['kompetensi', 'referensi']);
        });
        Schema::table('cpl_prodi', function (Blueprint $table) {
            $table->dropColumn('referensi');
        });
        Schema::table('pl', function (Blueprint $table) {
            $table->dropColumn('referensi');
        });
        Schema::table('mata_kuliah', function (Blueprint $table) {
            $table->dropColumn('kompetensi_mk');
        });
    }
};
