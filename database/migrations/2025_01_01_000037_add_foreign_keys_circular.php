<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Circular FK: users.id_kurikulum → kurikulum.id
//              kurikulum.locked_by / dibuat_oleh / disahkan_oleh → users.id
// Kedua tabel harus sudah ada sebelum constraint ini bisa ditambahkan.
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('id_kurikulum', 'fk_users_kurikulum')
                  ->references('id')->on('kurikulum')
                  ->nullOnDelete();
        });

        Schema::table('kurikulum', function (Blueprint $table) {
            $table->foreign('locked_by', 'fk_kur_locked')
                  ->references('id')->on('users')
                  ->nullOnDelete();

            $table->foreign('dibuat_oleh', 'fk_kur_dibuat')
                  ->references('id')->on('users')
                  ->nullOnDelete();

            $table->foreign('disahkan_oleh', 'fk_kur_disahkan')
                  ->references('id')->on('users')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('kurikulum', function (Blueprint $table) {
            $table->dropForeign('fk_kur_disahkan');
            $table->dropForeign('fk_kur_dibuat');
            $table->dropForeign('fk_kur_locked');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('fk_users_kurikulum');
        });
    }
};
