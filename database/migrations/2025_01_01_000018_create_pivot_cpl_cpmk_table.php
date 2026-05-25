<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pivot_cpl_cpmk', function (Blueprint $table) {
            $table->unsignedBigInteger('id_cpl');
            $table->unsignedBigInteger('id_cpmk');
            $table->decimal('bobot', 5, 2)->default(1.00);

            $table->primary(['id_cpl', 'id_cpmk']);
            $table->foreign('id_cpl')->references('id')->on('cpl_prodi')->cascadeOnDelete();
            $table->foreign('id_cpmk')->references('id')->on('cpmk')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pivot_cpl_cpmk');
    }
};
