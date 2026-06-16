<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rps_header', function (Blueprint $table) {
            $table->unsignedTinyInteger('sks_teori')->nullable()->after('kode_dokumen');
            $table->unsignedTinyInteger('sks_praktikum')->nullable()->after('sks_teori');
        });
    }

    public function down(): void
    {
        Schema::table('rps_header', function (Blueprint $table) {
            $table->dropColumn(['sks_teori', 'sks_praktikum']);
        });
    }
};
