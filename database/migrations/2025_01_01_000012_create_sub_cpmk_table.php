<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sub_cpmk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_cpmk')->constrained('cpmk')->cascadeOnDelete();
            $table->string('kode_sub_cpmk', 25);
            $table->text('deskripsi');
            $table->decimal('bobot', 5, 2)->default(0.00);
            $table->integer('urutan')->default(0);

            $table->unique(['id_cpmk', 'kode_sub_cpmk'], 'uk_subcpmk_kode');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_cpmk');
    }
};
