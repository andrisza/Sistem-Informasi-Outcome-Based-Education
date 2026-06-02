<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cpmk_penilaian', function (Blueprint $table) {
            $table->decimal('skor_maks', 5, 2)->nullable()->after('id_cpmk')
                  ->comment('Skor maks per CPMK (default = sum bobot penilaian)');
        });
    }

    public function down(): void
    {
        Schema::table('cpmk_penilaian', function (Blueprint $table) {
            $table->dropColumn('skor_maks');
        });
    }
};
