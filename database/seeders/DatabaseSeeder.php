<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductCategory;
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
        $catAtk = ProductCategory::create(['name' => 'Alat Tulis Kantor']);
        $catBuku = ProductCategory::create(['name' => 'Buku Tulis & Gambar']);
        $catKertas = ProductCategory::create(['name' => 'Kertas']);

        // 3. Seed Brands
        $brandJoyko = Brand::create(['name' => 'Joyko', 'description' => 'Alat tulis kantor berkualitas']);
        $brandKenko = Brand::create(['name' => 'Kenko', 'description' => 'Alat tulis kantor ekonomis']);
        $brandSidu = Brand::create(['name' => 'Sinar Dunia', 'description' => 'Kertas dan buku tulis']);

        // 4. Seed Products
        $product1 = Product::create([
            'name' => 'Pulpen Hitam Gel 0.5mm',
            'description' => 'Pulpen gel anti macet',
            'category_id' => $catAtk->id,
            'attachments' => [],
        ]);

        $product2 = Product::create([
            'name' => 'Buku Tulis Sidu 38 Lembar',
            'description' => 'Buku tulis garis biasa',
            'category_id' => $catBuku->id,
            'attachments' => [],
        ]);

        $product3 = Product::create([
            'name' => 'Kertas HVS A4 80GSM',
            'description' => 'Kertas putih bersih',
            'category_id' => $catKertas->id,
            'attachments' => [],
        ]);

        // Insert to product_brands (Pivot table that holds price directly via DB query because model not ready)
        DB::table('product_brands')->insert([
            ['id' => Str::uuid(), 'product_id' => $product1->id, 'brand_id' => $brandJoyko->id, 'unit' => 'Pcs', 'selling_price' => 3000, 'created_at' => now()],
            ['id' => Str::uuid(), 'product_id' => $product2->id, 'brand_id' => $brandSidu->id, 'unit' => 'Pack', 'selling_price' => 25000, 'created_at' => now()],
            ['id' => Str::uuid(), 'product_id' => $product3->id, 'brand_id' => $brandSidu->id, 'unit' => 'Rim', 'selling_price' => 50000, 'created_at' => now()],
        ]);

        // 5. Seed Services
        Service::create([
            'name' => 'Fotokopi Hitam Putih A4',
            'description' => 'Jasa fotokopi teks dokumen standar',
            'piece_price' => 250,
            'created_by' => $owner->id,
        ]);

        Service::create([
            'name' => 'Print Warna A4 Full',
            'description' => 'Print warna tinta berkualitas tinggi',
            'piece_price' => 1500,
            'created_by' => $owner->id,
        ]);

        Service::create([
            'name' => 'Jilid Spiral Kawat',
            'description' => 'Jasa jilid dokumen menggunakan spiral kawat besi',
            'piece_price' => 15000,
            'created_by' => $employee->id,
        ]);

        // 6. Seed Payment Methods
        DB::table('payment_methods')->insert([
            ['id' => Str::uuid(), 'code' => 'CASH', 'name' => 'Cash', 'is_active' => true, 'created_at' => now()],
            ['id' => Str::uuid(), 'code' => 'QRIS', 'name' => 'QRIS', 'is_active' => true, 'created_at' => now()],
            ['id' => Str::uuid(), 'code' => 'TRANSFER', 'name' => 'Bank Transfer', 'is_active' => true, 'created_at' => now()],
        ]);
    }
}
