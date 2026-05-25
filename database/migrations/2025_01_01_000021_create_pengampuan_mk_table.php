<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengampuan_mk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_mk')->constrained('mata_kuliah')->cascadeOnDelete();
            $table->unsignedBigInteger('id_dosen');
            $table->foreignId('id_semester')->constrained('semester_akademik')->cascadeOnDelete();
            $table->tinyInteger('is_koordinator')->default(0);
            $table->timestamp('created_at')->nullable();

            $table->unique(['id_mk', 'id_dosen', 'id_semester'], 'uk_pengampuan');
            $table->index(['id_dosen', 'id_semester'], 'idx_pengampuan_dosen');
            $table->foreign('id_dosen')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengampuan_mk');
    }
};
