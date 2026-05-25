<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('email', 150)->unique('uk_users_email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['kaprodi', 'tim_kurikulum', 'dosen', 'mahasiswa']);
            $table->string('identifier', 25)->nullable();
            $table->string('program_studi', 150)->nullable();
            $table->string('fakultas', 150)->nullable();
            $table->string('perguruan_tinggi', 200)->nullable();
            $table->year('tahun_masuk')->nullable();
            $table->unsignedBigInteger('id_kurikulum')->nullable();
            $table->string('foto', 500)->nullable();
            $table->enum('status_aktif', ['aktif', 'nonaktif', 'cuti'])->default('aktif');
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->index('role', 'idx_users_role');
            $table->index('identifier', 'idx_users_identifier');
            $table->index(['tahun_masuk', 'role'], 'idx_users_angkatan');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
