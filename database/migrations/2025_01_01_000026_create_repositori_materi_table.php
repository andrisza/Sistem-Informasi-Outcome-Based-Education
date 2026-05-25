<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repositori_materi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_mk')->constrained('mata_kuliah')->cascadeOnDelete();
            $table->foreignId('id_semester')->constrained('semester_akademik')->cascadeOnDelete();
            $table->unsignedBigInteger('id_dosen');
            $table->foreignId('id_rps_pertemuan')->nullable()->constrained('rps_pertemuan')->nullOnDelete();
            $table->enum('jenis_file', ['modul', 'presentasi', 'referensi', 'tugas', 'video', 'lainnya']);
            $table->string('nama_file', 255);
            $table->string('file_path', 500);
            $table->integer('ukuran_kb')->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->softDeletes();

            $table->foreign('id_dosen')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repositori_materi');
    }
};
