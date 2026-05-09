<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductBrand;
use App\Models\Batch;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Users
        $owner = User::create([
            'name' => 'Owner E-Business',
            'username' => 'owner123',
            'email' => 'owner@example.com',
            'password' => Hash::make('password123'),
            'role' => 'owner',
            'is_active' => true,
        ]);

        $employee = User::create([
            'name' => 'Kasir 1',
            'username' => 'kasir1',
            'email' => 'kasir@example.com',
            'password' => Hash::make('password123'),
            'role' => 'employee',
            'is_active' => true,
            'created_by' => $owner->id,
        ]);

        $member = User::create([
            'name' => 'Pelanggan Setia',
            'username' => 'member1',
            'email' => 'member@example.com',
            'password' => Hash::make('password123'),
            'role' => 'member',
            'is_active' => true,
        ]);

        // 2. Seed Categories
        $categories = [
            'Alat Tulis' => ProductCategory::create(['name' => 'Alat Tulis']),
            'Desktop Stationary' => ProductCategory::create(['name' => 'Desktop Stationary']),
            'File Folder' => ProductCategory::create(['name' => 'File Folder']),
            'Produk Kertas' => ProductCategory::create(['name' => 'Produk Kertas']),
            'Produk Adhesive' => ProductCategory::create(['name' => 'Produk Adhesive']),
            'Papan Informasi' => ProductCategory::create(['name' => 'Papan Informasi']),
        ];

        // 3. Seed Brands
        $brands = [
            'Joyko' => Brand::create(['name' => 'Joyko', 'description' => 'Alat tulis kantor berkualitas']),
            'Pentel' => Brand::create(['name' => 'Pentel', 'description' => 'Peralatan menulis asal Jepang']),
            'Snowman' => Brand::create(['name' => 'Snowman', 'description' => 'Merek lokal untuk marker dan spidol']),
            'Zebra' => Brand::create(['name' => 'Zebra', 'description' => 'Pena dan alat tulis premium']),
            'Pilot' => Brand::create(['name' => 'Pilot', 'description' => 'Teknologi pulpen canggih']),
            'Faber Castell' => Brand::create(['name' => 'Faber Castell', 'description' => 'Alat tulis legendaris']),
            'Sinar Dunia' => Brand::create(['name' => 'Sinar Dunia', 'description' => 'Kertas dan buku tulis berkualitas']),
        ];

        // 4. Seed Products & ProductBrands & Batches
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
            // Create Product
            $product = Product::create([
                'name' => $def['name'],
                'description' => 'Deskripsi untuk ' . $def['name'],
                'category_id' => $categories[$def['category']]->id,
                'attachments' => [], // Nanti bisa diisi dengan image dari Storage
            ]);

            // Create ProductBrand (Pivot Relation dengan harga jual akhir)
            $productBrand = ProductBrand::create([
                'product_id' => $product->id,
                'brand_id' => $brands[$def['brand']]->id,
                'unit' => $def['unit'],
                'selling_price' => $def['price'],
            ]);

            // Create Batch (Stok Masuk)
            if ($def['stock'] > 0) {
                Batch::create([
                    'batch_code' => 'BATCH-' . date('Ymd') . '-' . str_pad($idx + 1, 3, '0', STR_PAD_LEFT),
                    'current_stock' => $def['stock'],
                    'initial_stock' => $def['stock'],
                    'purchase_price' => $def['price'] * 0.7, // Margin keuntungan 30%
                    'is_active' => true,
                    'product_brand_id' => $productBrand->id,
                    'created_by' => $owner->id,
                    'created_at' => now(),
                ]);
            }
        }

        // 5. Seed Services (Jasa Cetak)
        Service::create([
            'name' => 'Pencetakan Dokumen (HVS A4)',
            'description' => 'Jasa fotokopi dan print teks dokumen standar hitam putih maupun warna',
            'piece_price' => 500,
            'created_by' => $owner->id,
        ]);

        Service::create([
            'name' => 'Penjilidan Dokumen',
            'description' => 'Jasa jilid dokumen menggunakan spiral kawat besi, lakban, dan soft/hard cover',
            'piece_price' => 15000,
            'created_by' => $employee->id,
        ]);
        
        Service::create([
            'name' => 'Cetak Banner & Poster',
            'description' => 'Cetak banner MMT, spanduk, X-Banner, dan Poster A3+',
            'piece_price' => 35000,
            'created_by' => $owner->id,
        ]);

        Service::create([
            'name' => 'Merchandise Custom',
            'description' => 'Cetak pin, mug, stiker custom, dan lanyard',
            'piece_price' => 12500,
            'created_by' => $owner->id,
        ]);

        // 6. Seed Payment Methods
        DB::table('payment_methods')->insert([
            ['id' => Str::uuid(), 'code' => 'CASH', 'name' => 'Cash', 'is_active' => true, 'created_at' => now()],
            ['id' => Str::uuid(), 'code' => 'QRIS', 'name' => 'QRIS', 'is_active' => true, 'created_at' => now()],
            ['id' => Str::uuid(), 'code' => 'TRANSFER', 'name' => 'Bank Transfer', 'is_active' => true, 'created_at' => now()],
        ]);
    }
}
