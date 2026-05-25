<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tim_kurikulum', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_periode')->constrained('periode_kurikulum')->cascadeOnDelete();
            $table->foreignId('id_user')->constrained('users')->cascadeOnDelete();
            $table->string('jabatan_tim', 100)->nullable();
            $table->string('sk_nomor', 100)->nullable();
            $table->timestamp('created_at')->nullable();

            $table->unique(['id_periode', 'id_user'], 'uk_tim_periode_user');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tim_kurikulum');
    }
};
