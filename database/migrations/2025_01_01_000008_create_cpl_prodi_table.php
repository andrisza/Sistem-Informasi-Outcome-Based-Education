<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cpl_prodi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kurikulum')->constrained('kurikulum')->cascadeOnDelete();
            $table->string('kode_cpl', 10);
            $table->text('deskripsi');
            $table->enum('kategori', ['Sikap', 'Keterampilan Umum', 'Keterampilan Khusus', 'Pengetahuan']);
            $table->integer('urutan')->default(0);
            $table->softDeletes();

            $table->unique(['id_kurikulum', 'kode_cpl'], 'uk_cplp_kode');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cpl_prodi');
    }
};
