<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cpl_sndikti', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 15)->unique('uk_cplsn_kode');
            $table->text('deskripsi');
            $table->enum('kategori', ['Sikap', 'Keterampilan Umum', 'Keterampilan Khusus', 'Pengetahuan']);
            $table->integer('urutan')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cpl_sndikti');
    }
};
