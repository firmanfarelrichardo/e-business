<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductCategory;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\Service;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Generates sensible, realistic combinations of Categories, Brands, and Products.
     */
    public function run(): void
    {
        // --- 1. Categories ---
        $categoriesData = [
            'Kertas',
            'Alat Tulis',
            'Tinta & Toner',
            'Peralatan Kantor',
            'Buku & Jurnal'
        ];
        
        $categories = [];
        foreach ($categoriesData as $name) {
            $categories[$name] = ProductCategory::create([
                'id' => (string) Str::uuid(),
                'name' => $name,
            ]);
        }

        // --- 2. Brands ---
        $brandsData = [
            'Sinar Dunia',
            'PaperOne',
            'Joyko',
            'Kenko',
            'Snowman',
            'Epson',
            'Bantex',
            'Faber-Castell'
        ];

        $brands = [];
        foreach ($brandsData as $name) {
            $brands[$name] = Brand::create([
                'id' => (string) Str::uuid(),
                'name' => $name,
            ]);
        }

        // --- 3. Products & ProductBrands (Variants) ---
        $productBlueprints = [
            [
                'name' => 'Kertas HVS A4 70gsm',
                'category' => 'Kertas',
                'variants' => [
                    ['brand' => 'Sinar Dunia', 'unit' => 'Rim', 'selling_price' => 45000],
                    ['brand' => 'PaperOne', 'unit' => 'Rim', 'selling_price' => 48000],
                ]
            ],
            [
                'name' => 'Kertas HVS F4 70gsm',
                'category' => 'Kertas',
                'variants' => [
                    ['brand' => 'Sinar Dunia', 'unit' => 'Rim', 'selling_price' => 50000],
                ]
            ],
            [
                'name' => 'Pulpen Gel 0.5mm Hitam',
                'category' => 'Alat Tulis',
                'variants' => [
                    ['brand' => 'Joyko', 'unit' => 'Pack', 'selling_price' => 25000],
                    ['brand' => 'Kenko', 'unit' => 'Pack', 'selling_price' => 27000],
                ]
            ],
            [
                'name' => 'Spidol Whiteboard',
                'category' => 'Alat Tulis',
                'variants' => [
                    ['brand' => 'Snowman', 'unit' => 'Pcs', 'selling_price' => 8500],
                    ['brand' => 'Joyko', 'unit' => 'Pcs', 'selling_price' => 7500],
                ]
            ],
            [
                'name' => 'Tinta Botol Original Hitam (664)',
                'category' => 'Tinta & Toner',
                'variants' => [
                    ['brand' => 'Epson', 'unit' => 'Botol', 'selling_price' => 85000],
                ]
            ],
            [
                'name' => 'Ordner Folio 7cm',
                'category' => 'Peralatan Kantor',
                'variants' => [
                    ['brand' => 'Bantex', 'unit' => 'Pcs', 'selling_price' => 25000],
                    ['brand' => 'Joyko', 'unit' => 'Pcs', 'selling_price' => 21000],
                ]
            ],
            [
                'name' => 'Pensil 2B',
                'category' => 'Alat Tulis',
                'variants' => [
                    ['brand' => 'Faber-Castell', 'unit' => 'Lusin', 'selling_price' => 35000],
                    ['brand' => 'Joyko', 'unit' => 'Lusin', 'selling_price' => 20000],
                ]
            ],
            [
                'name' => 'Buku Tulis Lined 58 Lembar',
                'category' => 'Buku & Jurnal',
                'variants' => [
                    ['brand' => 'Sinar Dunia', 'unit' => 'Pack (10 Buku)', 'selling_price' => 38000],
                    ['brand' => 'Joyko', 'unit' => 'Pack (10 Buku)', 'selling_price' => 35000],
                ]
            ],
            [
                'name' => 'Correction Pen (Tipe-X)',
                'category' => 'Alat Tulis',
                'variants' => [
                    ['brand' => 'Kenko', 'unit' => 'Pcs', 'selling_price' => 5000],
                    ['brand' => 'Joyko', 'unit' => 'Pcs', 'selling_price' => 4500],
                ]
            ],
            [
                'name' => 'Kertas Foto Glossy A4',
                'category' => 'Kertas',
                'variants' => [
                    ['brand' => 'Epson', 'unit' => 'Pack', 'selling_price' => 60000],
                    ['brand' => 'PaperOne', 'unit' => 'Pack', 'selling_price' => 45000],
                ]
            ],
        ];

        foreach ($productBlueprints as $bp) {
            $product = Product::create([
                'id' => (string) Str::uuid(),
                'category_id' => $categories[$bp['category']]->id,
                'name' => $bp['name'],
            ]);

            foreach ($bp['variants'] as $variant) {
                ProductBrand::create([
                    'id' => (string) Str::uuid(),
                    'product_id' => $product->id,
                    'brand_id' => $brands[$variant['brand']]->id,
                    'unit' => $variant['unit'],
                    'selling_price' => $variant['selling_price'], // Will be overwritten by WAC later if batches exist
                ]);
            }
        }

        // --- 4. Services (Jasa) ---
        $servicesData = [
            ['name' => 'Fotocopy Hitam Putih (A4/F4)', 'piece_price' => 250],
            ['name' => 'Print Warna A4 (Teks)', 'piece_price' => 1000],
            ['name' => 'Print Warna A4 (Full Gambar)', 'piece_price' => 3000],
            ['name' => 'Jilid Hardcover Skripsi', 'piece_price' => 35000],
            ['name' => 'Jilid Spiral Kawat', 'piece_price' => 15000],
            ['name' => 'Laminating KTP/Card', 'piece_price' => 3000],
            ['name' => 'Laminating F4/A4', 'piece_price' => 5000],
        ];

        foreach ($servicesData as $sd) {
            Service::create([
                'id' => (string) Str::uuid(),
                'name' => $sd['name'],
                'piece_price' => $sd['piece_price'],
            ]);
        }
    }
}
