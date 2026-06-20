<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Membuat satu akun admin default (Kaprodi) untuk login awal di produksi.
 * Tidak mengisi data contoh apa pun — database tetap bersih.
 *
 * Jalankan: php artisan db:seed --class=AdminUserSeeder
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'kaprodi@si-obe.id'],
            [
                'name'          => 'Administrator Kaprodi',
                'password'      => Hash::make('password'),
                'role'          => Role::Kaprodi,
                'program_studi' => 'Sistem Informasi',
                'status_aktif'  => 'aktif',
            ]
        );

        $this->command->info('✅ Akun kaprodi siap: kaprodi@si-obe.id / password');
    }
}
