<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\User;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::where('role', 'owner')->first();
        $employee = User::where('role', 'employee')->first();

        Service::updateOrCreate(
            ['name' => 'Pencetakan Dokumen (HVS A4)'],
            ['description' => 'Jasa fotokopi dan print teks dokumen standar hitam putih maupun warna', 'piece_price' => 500, 'created_by' => $owner?->id]
        );

        Service::updateOrCreate(
            ['name' => 'Penjilidan Dokumen'],
            ['description' => 'Jasa jilid dokumen menggunakan spiral kawat besi, lakban, dan soft/hard cover', 'piece_price' => 15000, 'created_by' => $employee?->id ?? $owner?->id]
        );

        Service::updateOrCreate(
            ['name' => 'Cetak Banner & Poster'],
            ['description' => 'Cetak banner MMT, spanduk, X-Banner, dan Poster A3+', 'piece_price' => 35000, 'created_by' => $owner?->id]
        );

        Service::updateOrCreate(
            ['name' => 'Merchandise Custom'],
            ['description' => 'Cetak pin, mug, stiker custom, dan lanyard', 'piece_price' => 12500, 'created_by' => $owner?->id]
        );
    }
}
