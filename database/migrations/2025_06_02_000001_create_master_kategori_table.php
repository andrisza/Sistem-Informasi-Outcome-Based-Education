<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_kategori', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis', ['pl', 'cpl', 'bk', 'mk']);
            $table->string('nama', 150);
            $table->text('deskripsi')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();

            $table->unique(['jenis', 'nama']);
        });

        // Seed default PL categories
        $pl = [
            ['PL Penciri Utama',                    1],
            ['PL Tambahan KK dan P',                2],
            ['PL Sikap',                            3],
            ['PL Keterampilan Umum dan Sikap',      4],
        ];
        foreach ($pl as [$nama, $urutan]) {
            DB::table('master_kategori')->insert([
                'jenis'      => 'pl',
                'nama'       => $nama,
                'urutan'     => $urutan,
                'is_aktif'   => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Seed default CPL categories
        $cpl = [
            ['Sikap',                 1],
            ['Keterampilan Umum',     2],
            ['Keterampilan Khusus',   3],
            ['Pengetahuan',           4],
        ];
        foreach ($cpl as [$nama, $urutan]) {
            DB::table('master_kategori')->insert([
                'jenis'      => 'cpl',
                'nama'       => $nama,
                'urutan'     => $urutan,
                'is_aktif'   => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Seed default BK categories (bidang_keilmuan)
        $bk = [
            ['Ilmu Dasar',              1],
            ['Ilmu Terapan',            2],
            ['Teknologi Informasi',     3],
            ['Matematika & Statistika', 4],
            ['Manajemen Bisnis',        5],
            ['Sosial Humaniora',        6],
        ];
        foreach ($bk as [$nama, $urutan]) {
            DB::table('master_kategori')->insert([
                'jenis'      => 'bk',
                'nama'       => $nama,
                'urutan'     => $urutan,
                'is_aktif'   => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Seed default MK categories
        $mk = [
            ['Wajib',   1],
            ['Pilihan',  2],
            ['MKWK',    3],
            ['MKDU',    4],
        ];
        foreach ($mk as [$nama, $urutan]) {
            DB::table('master_kategori')->insert([
                'jenis'      => 'mk',
                'nama'       => $nama,
                'urutan'     => $urutan,
                'is_aktif'   => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('master_kategori');
    }
};
