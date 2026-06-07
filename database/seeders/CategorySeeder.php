<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductCategory;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Alat Tulis',
            'Desktop Stationary',
            'File Folder',
            'Produk Kertas',
            'Produk Adhesive',
            'Papan Informasi'
        ];

        foreach ($categories as $cat) {
            ProductCategory::updateOrCreate(['name' => $cat]);
        }
    }
}
