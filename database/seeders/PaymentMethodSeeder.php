<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            'CASH' => 'Cash',
            'QRIS' => 'QRIS',
            'TRANSFER' => 'Bank Transfer'
        ];

        foreach ($methods as $code => $name) {
            $exists = DB::table('payment_methods')->where('code', $code)->exists();
            if (!$exists) {
                DB::table('payment_methods')->insert([
                    'id' => Str::uuid(),
                    'code' => $code,
                    'name' => $name,
                    'is_active' => true,
                    'created_at' => now()
                ]);
            }
        }
    }
}
