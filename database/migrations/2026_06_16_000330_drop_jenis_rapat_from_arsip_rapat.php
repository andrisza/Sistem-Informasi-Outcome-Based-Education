<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('arsip_rapat', function (Blueprint $table) {
            if (Schema::hasColumn('arsip_rapat', 'jenis_rapat')) {
                $table->dropColumn('jenis_rapat');
            }
        });
    }

    public function down(): void
    {
        Schema::table('arsip_rapat', function (Blueprint $table) {
            $table->enum('jenis_rapat', [
                'pleno_kurikulum', 'rapat_tim_kecil', 'rapat_stakeholder',
                'rapat_evaluasi', 'rapat_cqi', 'rapat_lainnya',
            ])->after('judul_rapat')->default('rapat_lainnya');
        });
    }
};
