<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mata_kuliah', function (Blueprint $table) {
            // Jalur konsentrasi untuk MK Pilihan: Manajemen TI / Data Analytics / Bisnis Digital
            $table->string('konsentrasi', 100)->nullable()->after('kode_prasyarat');
        });
    }

    public function down(): void
    {
        Schema::table('mata_kuliah', function (Blueprint $table) {
            $table->dropColumn('konsentrasi');
        });
    }
};
