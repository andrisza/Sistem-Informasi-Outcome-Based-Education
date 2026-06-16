<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus FK id_periode terlebih dahulu (jika ada)
        try {
            Schema::table('arsip_rapat', fn (Blueprint $t) => $t->dropForeign(['id_periode']));
        } catch (\Throwable) {}

        Schema::table('arsip_rapat', function (Blueprint $table) {
            $drop = array_filter(
                ['id_periode', 'agenda', 'kesimpulan', 'tindak_lanjut', 'peserta', 'file_notulen', 'file_daftar_hadir'],
                fn ($col) => Schema::hasColumn('arsip_rapat', $col)
            );
            if ($drop) {
                $table->dropColumn(array_values($drop));
            }
        });
    }

    public function down(): void
    {
        Schema::table('arsip_rapat', function (Blueprint $table) {
            $table->text('agenda')->nullable()->after('tempat');
            $table->text('kesimpulan')->nullable()->after('notulen');
            $table->text('tindak_lanjut')->nullable()->after('kesimpulan');
            $table->json('peserta')->nullable()->after('tindak_lanjut');
            $table->string('file_notulen', 500)->nullable()->after('peserta');
            $table->string('file_daftar_hadir', 500)->nullable()->after('file_notulen');
        });
    }
};
