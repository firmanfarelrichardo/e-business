<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Owner
        User::updateOrCreate(
            ['email' => 'owner@sinergi.com'], // Patokan agar tidak dobel
            [
                'name' => 'Pemilik Sinergi',
                'username' => 'owner',
                'password' => Hash::make('password123'),
                'role' => 'owner',
                'is_active' => true,
                'address' => 'Gedung Pusat Sinergi',
            ]
        );

        // 2. Employee (Kasir/Staff)
        User::updateOrCreate(
            ['email' => 'employee@sinergi.com'], // Patokan agar tidak dobel
            [
                'name' => 'Kasir Utama',
                'username' => 'employee',
                'password' => Hash::make('password123'),
                'role' => 'employee',
                'is_active' => true,
                'address' => 'Cabang Sinergi Sudirman',
            ]
        );

        // 3. Member (Pelanggan)
        User::updateOrCreate(
            ['email' => 'member@sinergi.com'], // Patokan agar tidak dobel
            [
                'name' => 'Pelanggan Setia',
                'username' => 'member',
                'password' => Hash::make('password123'),
                'role' => 'member',
                'is_active' => true,
            ]
        );
    }
}
