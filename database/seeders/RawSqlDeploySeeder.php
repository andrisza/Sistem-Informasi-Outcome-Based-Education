<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class RawSqlDeploySeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('sql/si_obe.sql');

        if (File::exists($path)) {
            $this->command->info('Sedang mengimport seluruh database si_obe...');
            
            // 1. Matikan sementara sistem pengecekan Foreign Key di MySQL
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            $sql = File::get($path);
            
            // 2. Eksekusi seluruh file SQL (termasuk DROP tabel lama)
            DB::unprepared($sql);
            
            // 3. Nyalakan kembali sistem pengecekan Foreign Key agar database tetap aman
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            $this->command->info('Database si_obe berhasil terdeploy seluruhnya!');
        } else {
            $this->command->error('File si_obe.sql tidak ditemukan di database/sql/');
        }
    }
}