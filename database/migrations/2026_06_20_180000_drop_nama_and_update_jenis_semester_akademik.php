<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Migrasikan nilai jenis lama ke enum baru (Gasal/Genap).
        DB::statement("ALTER TABLE semester_akademik MODIFY jenis ENUM('Ganjil','Gasal','Genap','Pendek') NOT NULL");
        DB::table('semester_akademik')->where('jenis', 'Ganjil')->update(['jenis' => 'Gasal']);
        DB::table('semester_akademik')->where('jenis', 'Pendek')->update(['jenis' => 'Genap']);
        DB::statement("ALTER TABLE semester_akademik MODIFY jenis ENUM('Gasal','Genap') NOT NULL");

        // 2. Hapus kolom nama (redundan — diturunkan dari jenis + tahun_akademik).
        Schema::table('semester_akademik', function (Blueprint $table) {
            $table->dropUnique('uk_semester_nama');
            $table->dropColumn('nama');
        });

        // 3. Cegah duplikasi semester via kombinasi tahun_akademik + jenis.
        Schema::table('semester_akademik', function (Blueprint $table) {
            $table->unique(['tahun_akademik', 'jenis'], 'uk_semester_thn_jenis');
        });
    }

    public function down(): void
    {
        Schema::table('semester_akademik', function (Blueprint $table) {
            $table->dropUnique('uk_semester_thn_jenis');
            $table->string('nama', 30)->nullable()->after('id');
        });

        // Isi ulang nama dari jenis + tahun_akademik untuk baris yang ada.
        DB::statement("UPDATE semester_akademik SET nama = CONCAT(jenis, ' ', tahun_akademik)");

        Schema::table('semester_akademik', function (Blueprint $table) {
            $table->unique('nama', 'uk_semester_nama');
        });

        DB::statement("ALTER TABLE semester_akademik MODIFY jenis ENUM('Ganjil','Genap','Pendek') NOT NULL");
    }
};
