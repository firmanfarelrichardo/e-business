<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Generates a realistic set of users for E-Business hierarchy.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $password = Hash::make('password'); // Default password for testing

        // 1. Create System Owner (Admin)
        User::create([
            'id' => (string) Str::uuid(),
            'name' => 'Owner Sinergi',
            'username' => 'admin',
            'email' => 'admin@sinergi.com',
            'password' => $password,
            'role' => 'owner',
            'email_verified_at' => now(),
        ]);

        // 2. Create Employees (Cashiers)
        User::create([
            'id' => (string) Str::uuid(),
            'name' => 'Kasir Utama',
            'username' => 'kasir1',
            'email' => 'kasir1@sinergi.com',
            'password' => $password,
            'role' => 'employee',
            'email_verified_at' => now(),
        ]);

        User::create([
            'id' => (string) Str::uuid(),
            'name' => 'Elthon Jhon Kevin',
            'username' => 'elthonJhon_',
            'email' => 'elthonjhonkevin@gmail.com',
            'password' => $password,
            'role' => 'member',
            'email_verified_at' => now(),
        ]);

        // 3. Generate 50+ Real-world Customers
        $customers = [];
        for ($i = 0; $i < 55; $i++) {
            $customers[] = [
                'id' => (string) Str::uuid(),
                'name' => $faker->name(),
                'username' => $faker->unique()->userName(),
                'email' => $faker->unique()->safeEmail(),
                'password' => $password,
                'role' => 'member',
                'email_verified_at' => $faker->optional(0.8)->dateTimeThisYear(),
                'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
                'updated_at' => now(),
            ];
        }

        // Bulk insert for performance
        User::insert($customers);
    }
}
