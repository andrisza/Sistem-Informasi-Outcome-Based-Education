<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pivot_cpl_bk_mk', function (Blueprint $table) {
            $table->unsignedBigInteger('id_cpl');
            $table->unsignedBigInteger('id_bk');
            $table->unsignedBigInteger('id_mk');

            $table->primary(['id_cpl', 'id_bk', 'id_mk']);
            $table->foreign('id_cpl')->references('id')->on('cpl_prodi')->cascadeOnDelete();
            $table->foreign('id_bk')->references('id')->on('bahan_kajian')->cascadeOnDelete();
            $table->foreign('id_mk')->references('id')->on('mata_kuliah')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pivot_cpl_bk_mk');
    }
};
