<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('komponen_asesmen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_mk')->constrained('mata_kuliah')->cascadeOnDelete();
            $table->foreignId('id_semester')->constrained('semester_akademik')->cascadeOnDelete();
            $table->foreignId('id_sub_cpmk')->nullable()->constrained('sub_cpmk')->nullOnDelete();
            $table->string('nama_komponen', 100);
            $table->enum('jenis_komponen', ['Partisipasi', 'Observasi', 'Unjuk Kerja', 'UTS', 'UAS', 'Lainnya']);
            $table->decimal('bobot_persen', 5, 2);
            $table->decimal('skor_maks', 5, 2)->default(100.00);

            $table->index(['id_mk', 'id_semester'], 'idx_komponen_mk_smt');
        });

        DB::statement('ALTER TABLE komponen_asesmen ADD CONSTRAINT chk_bobot CHECK (bobot_persen BETWEEN 0 AND 100)');
    }

    public function down(): void
    {
        Schema::dropIfExists('komponen_asesmen');
    }
};
