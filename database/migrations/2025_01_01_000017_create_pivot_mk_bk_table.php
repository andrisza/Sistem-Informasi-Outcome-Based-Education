<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pivot_mk_bk', function (Blueprint $table) {
            $table->unsignedBigInteger('id_mk');
            $table->unsignedBigInteger('id_bk');

            $table->primary(['id_mk', 'id_bk']);
            $table->foreign('id_mk')->references('id')->on('mata_kuliah')->cascadeOnDelete();
            $table->foreign('id_bk')->references('id')->on('bahan_kajian')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pivot_mk_bk');
    }
};
