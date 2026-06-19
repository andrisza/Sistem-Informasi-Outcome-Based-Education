<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop FK that references the index, then the index itself
        Schema::table('arsip_rapat', function (Blueprint $table) {
            $table->dropForeign('arsip_rapat_id_kurikulum_foreign');
            $table->dropIndex('idx_rapat_kur');
        });

        Schema::table('arsip_rapat', function (Blueprint $table) {
            $table->renameColumn('judul_rapat', 'judul_kegiatan');
        });

        Schema::table('arsip_rapat', function (Blueprint $table) {
            $table->renameColumn('tanggal_rapat', 'tanggal');
        });

        Schema::table('arsip_rapat', function (Blueprint $table) {
            $table->longText('tindak_lanjut')->nullable()->after('notulen');
        });

        Schema::table('arsip_rapat', function (Blueprint $table) {
            $table->renameColumn('notulen', 'temuan');
        });

        // Recreate index + FK on the renamed column
        Schema::table('arsip_rapat', function (Blueprint $table) {
            $table->index(['id_kurikulum', 'tanggal'], 'idx_rapat_kur');
            $table->foreign('id_kurikulum', 'arsip_rapat_id_kurikulum_foreign')
                  ->references('id')->on('kurikulum')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('arsip_rapat', function (Blueprint $table) {
            $table->dropForeign('arsip_rapat_id_kurikulum_foreign');
            $table->dropIndex('idx_rapat_kur');
        });

        Schema::table('arsip_rapat', function (Blueprint $table) {
            $table->renameColumn('judul_kegiatan', 'judul_rapat');
        });

        Schema::table('arsip_rapat', function (Blueprint $table) {
            $table->renameColumn('tanggal', 'tanggal_rapat');
        });

        Schema::table('arsip_rapat', function (Blueprint $table) {
            $table->renameColumn('temuan', 'notulen');
        });

        Schema::table('arsip_rapat', function (Blueprint $table) {
            $table->dropColumn('tindak_lanjut');
        });

        Schema::table('arsip_rapat', function (Blueprint $table) {
            $table->index(['id_kurikulum', 'tanggal_rapat'], 'idx_rapat_kur');
            $table->foreign('id_kurikulum', 'arsip_rapat_id_kurikulum_foreign')
                  ->references('id')->on('kurikulum')->onDelete('cascade');
        });
    }
};
