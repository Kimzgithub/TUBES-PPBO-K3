<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'demo@untan.ac.id'],
            [
                'name' => 'Mahasiswa Demo K3',
                'username' => 'mahasiswak3',
                'password' => Hash::make('password123'),
                'bio' => 'Akun uji coba Instagram Clone Kelompok 3.',
                'notify_via_email' => true,
            ]
        );

        User::factory(5)->create();
    }
}