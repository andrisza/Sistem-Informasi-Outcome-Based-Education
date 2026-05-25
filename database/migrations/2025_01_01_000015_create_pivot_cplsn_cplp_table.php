<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pivot_cplsn_cplp', function (Blueprint $table) {
            $table->unsignedBigInteger('id_cpl_sndikti');
            $table->unsignedBigInteger('id_cpl_prodi');

            $table->primary(['id_cpl_sndikti', 'id_cpl_prodi']);
            $table->foreign('id_cpl_sndikti')->references('id')->on('cpl_sndikti')->cascadeOnDelete();
            $table->foreign('id_cpl_prodi')->references('id')->on('cpl_prodi')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pivot_cplsn_cplp');
    }
};
