<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batas_ketercapaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_cpl')->constrained('cpl_prodi')->cascadeOnDelete();
            $table->foreignId('id_kurikulum')->constrained('kurikulum')->cascadeOnDelete();
            $table->decimal('batas_nilai', 5, 2)->default(70.00);

            $table->unique(['id_cpl', 'id_kurikulum'], 'uk_batas');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batas_ketercapaian');
    }
};
