<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pivot_cpl_bk', function (Blueprint $table) {
            $table->unsignedBigInteger('id_cpl');
            $table->unsignedBigInteger('id_bk');

            $table->primary(['id_cpl', 'id_bk']);
            $table->foreign('id_cpl')->references('id')->on('cpl_prodi')->cascadeOnDelete();
            $table->foreign('id_bk')->references('id')->on('bahan_kajian')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pivot_cpl_bk');
    }
};
