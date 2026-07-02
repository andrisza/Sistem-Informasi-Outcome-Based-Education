<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class RawSqlDeploySeeder extends Seeder
{
    public function run(): void
    {
        // Jalur menuju file SQL mentah
        $path = database_path('sql/si_obe.sql');

        if (File::exists($path)) {
            $this->command->info('Sedang mengimport seluruh database lokal...');
            
            // Membaca isi file SQL
            $sql = File::get($path);
            
            // Mengeksekusi seluruh query SQL mentah tanpa terkecuali
            DB::unprepared($sql);
            
            $this->command->info('Database lokal berhasil terdeploy seluruhnya!');
        } else {
            $this->command->error('File dump.sql tidak ditemukan di database/sql/');
        }
    }
}