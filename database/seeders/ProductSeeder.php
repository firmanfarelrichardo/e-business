<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Product, ProductCategory, Brand, ProductBrand, Batch, User};

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::where('role', 'owner')->first() ?? User::first();

        $productDefinitions = [
            [
                'name' => 'Joyko Pensil Mekanik 0.5mm',
                'category' => 'Alat Tulis',
                'brand' => 'Joyko',
                'price' => 5000,
                'stock' => 100,
                'unit' => 'Pcs',
            ],
            [
                'name' => 'Pentel Permanent Marker Hitam',
                'category' => 'Alat Tulis',
                'brand' => 'Pentel',
                'price' => 8000,
                'stock' => 50,
                'unit' => 'Pcs',
            ],
            [
                'name' => 'Snowman Refill Ballpoint V-5',
                'category' => 'Alat Tulis',
                'brand' => 'Snowman',
                'price' => 16000,
                'stock' => 200,
                'unit' => 'Pack',
            ],
            [
                'name' => 'Zebra Sarasa Snoopy Edition',
                'category' => 'Alat Tulis',
                'brand' => 'Zebra',
                'price' => 30000,
                'stock' => 20,
                'unit' => 'Pcs',
            ],
            [
                'name' => 'Pilot Frixion 3 Warna',
                'category' => 'Alat Tulis',
                'brand' => 'Pilot',
                'price' => 175000,
                'stock' => 15,
                'unit' => 'Pack',
            ],
            [
                'name' => 'Zebra Ballpoint Sarasa Clip',
                'category' => 'Alat Tulis',
                'brand' => 'Zebra',
                'price' => 20000,
                'stock' => 75,
                'unit' => 'Pcs',
            ],
            [
                'name' => 'Faber Castell Highlighter',
                'category' => 'Alat Tulis',
                'brand' => 'Faber Castell',
                'price' => 15000,
                'stock' => 0, // Sengaja dikosongkan untuk tes status "Sold Out"
                'unit' => 'Pcs',
            ],
            [
                'name' => 'Kertas HVS A4 80GSM Sinar Dunia',
                'category' => 'Produk Kertas',
                'brand' => 'Sinar Dunia',
                'price' => 55000,
                'stock' => 30,
                'unit' => 'Rim',
            ],
            [
                'name' => 'Buku Tulis Sidu 38 Lembar',
                'category' => 'Produk Kertas',
                'brand' => 'Sinar Dunia',
                'price' => 25000,
                'stock' => 120,
                'unit' => 'Pack',
            ]
        ];

        foreach ($productDefinitions as $idx => $def) {
            $cat = ProductCategory::where('name', $def['category'])->first();
            $product = Product::updateOrCreate(
                ['name' => $def['name']],
                [
                    'description' => 'Deskripsi untuk ' . $def['name'],
                    'category_id' => $cat->id ?? null,
                    'attachments' => [],
                ]
            );

            $brand = Brand::where('name', $def['brand'])->first();
            $productBrand = ProductBrand::updateOrCreate(
                ['product_id' => $product->id, 'brand_id' => $brand->id ?? null],
                [
                    'unit' => $def['unit'],
                    'selling_price' => $def['price'],
                ]
            );

            if ($def['stock'] > 0) {
                $batchCode = 'BATCH-' . date('Ymd') . '-' . str_pad($idx + 1, 3, '0', STR_PAD_LEFT);
                Batch::firstOrCreate(
                    ['batch_code' => $batchCode],
                    [
                        'current_stock' => $def['stock'],
                        'initial_stock' => $def['stock'],
                        'purchase_price' => $def['price'] * 0.7,
                        'is_active' => true,
                        'product_brand_id' => $productBrand->id,
                        'created_by' => $owner?->id,
                        'created_at' => now(),
                    ]
                );
            }
        }
    }
}
