<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'Joyko', 'description' => 'Alat tulis kantor berkualitas'],
            ['name' => 'Pentel', 'description' => 'Peralatan menulis asal Jepang'],
            ['name' => 'Snowman', 'description' => 'Merek lokal untuk marker dan spidol'],
            ['name' => 'Zebra', 'description' => 'Pena dan alat tulis premium'],
            ['name' => 'Pilot', 'description' => 'Teknologi pulpen canggih'],
            ['name' => 'Faber Castell', 'description' => 'Alat tulis legendaris'],
            ['name' => 'Sinar Dunia', 'description' => 'Kertas dan buku tulis berkualitas'],
        ];

        foreach ($brands as $brand) {
            Brand::updateOrCreate(
                ['name' => $brand['name']],
                ['description' => $brand['description']]
            );
        }
    }
}
