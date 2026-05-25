<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rps_detail_mk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_rps')->constrained('rps_header')->cascadeOnDelete();
            $table->foreignId('id_cpl')->constrained('cpl_prodi')->cascadeOnDelete();
            $table->foreignId('id_cpmk')->constrained('cpmk')->cascadeOnDelete();
            $table->foreignId('id_sub_cpmk')->nullable()->constrained('sub_cpmk')->nullOnDelete();
            $table->integer('urutan')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rps_detail_mk');
    }
};
