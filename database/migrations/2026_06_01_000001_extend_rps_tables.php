<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // rps_header sudah punya kolom baru dari partial run sebelumnya,
        // pastikan kolom yang mungkin belum ada tetap ditambahkan.
        Schema::table('rps_header', function (Blueprint $table) {
            if (! Schema::hasColumn('rps_header', 'nama_perguruan_tinggi')) {
                $table->string('nama_perguruan_tinggi', 150)->nullable()->after('kode_dokumen');
            }
            if (! Schema::hasColumn('rps_header', 'nama_fakultas')) {
                $table->string('nama_fakultas', 150)->nullable()->after('nama_perguruan_tinggi');
            }
            if (! Schema::hasColumn('rps_header', 'deskripsi_mk')) {
                $table->text('deskripsi_mk')->nullable()->after('nama_fakultas');
            }
            if (! Schema::hasColumn('rps_header', 'materi_pembelajaran')) {
                $table->text('materi_pembelajaran')->nullable()->after('deskripsi_mk');
            }
            if (! Schema::hasColumn('rps_header', 'pustaka_utama')) {
                $table->text('pustaka_utama')->nullable()->after('materi_pembelajaran');
            }
            if (! Schema::hasColumn('rps_header', 'pustaka_pendukung')) {
                $table->text('pustaka_pendukung')->nullable()->after('pustaka_utama');
            }
        });

        // ── Ubah rps_pertemuan ──────────────────────────────────────────────
        // Buat index biasa pada id_rps agar FK tetap punya backing index
        // setelah unique composite dilepas.
        Schema::table('rps_pertemuan', function (Blueprint $table) {
            $table->index('id_rps', 'idx_rps_pertemuan_id_rps');
        });

        Schema::table('rps_pertemuan', function (Blueprint $table) {
            $table->dropUnique('uk_rps_minggu');
            $table->dropForeign(['id_sub_cpmk']);
        });

        Schema::table('rps_pertemuan', function (Blueprint $table) {
            $table->unsignedBigInteger('id_sub_cpmk')->nullable()->change();
            $table->foreign('id_sub_cpmk')->references('id')->on('sub_cpmk')->nullOnDelete();
            $table->text('kriteria_teknik')->nullable()->after('indikator_penilaian');
            $table->string('bentuk_luring', 300)->nullable()->after('kriteria_teknik');
            $table->string('bentuk_daring', 300)->nullable()->after('bentuk_luring');
            $table->decimal('bobot_penilaian', 5, 2)->nullable()->after('bentuk_daring');
            $table->unique(['id_rps', 'minggu_ke'], 'uk_rps_minggu');
        });
    }

    public function down(): void
    {
        Schema::table('rps_pertemuan', function (Blueprint $table) {
            $table->dropUnique('uk_rps_minggu');
            $table->dropForeign(['id_sub_cpmk']);
            $table->dropColumn(['kriteria_teknik', 'bentuk_luring', 'bentuk_daring', 'bobot_penilaian']);
            $table->unsignedBigInteger('id_sub_cpmk')->nullable(false)->change();
            $table->foreign('id_sub_cpmk')->references('id')->on('sub_cpmk')->cascadeOnDelete();
            $table->unique(['id_rps', 'minggu_ke'], 'uk_rps_minggu');
            $table->dropIndex('idx_rps_pertemuan_id_rps');
        });

        Schema::table('rps_header', function (Blueprint $table) {
            $table->dropColumn(['nama_perguruan_tinggi', 'nama_fakultas', 'deskripsi_mk',
                                 'materi_pembelajaran', 'pustaka_utama', 'pustaka_pendukung']);
        });
    }
};
