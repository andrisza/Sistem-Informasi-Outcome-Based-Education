<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pivot_pl_cpl', function (Blueprint $table) {
            $table->unsignedBigInteger('id_pl');
            $table->unsignedBigInteger('id_cpl');

            $table->primary(['id_pl', 'id_cpl']);
            $table->foreign('id_pl')->references('id')->on('pl')->cascadeOnDelete();
            $table->foreign('id_cpl')->references('id')->on('cpl_prodi')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pivot_pl_cpl');
    }
};
