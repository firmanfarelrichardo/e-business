<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductBrand;
use App\Models\Batch;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class BatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Generates stock batches for every product variant to simulate a living inventory.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $productBrands = ProductBrand::all();
        $adminUserId = User::where('role', 'owner')->first()->id;

        foreach ($productBrands as $pb) {
            $basePrice = $pb->selling_price * 0.7; // Assume base cost is 70% of selling price
            $numBatches = rand(3, 5);
            
            for ($i = 0; $i < $numBatches; $i++) {
                // Fluctuating purchase price (+/- 10%)
                $fluctuation = $basePrice * (rand(-10, 10) / 100);
                $purchasePrice = $basePrice + $fluctuation;
                
                // Realistic date distribution over last 6 months
                $createdAt = $faker->dateTimeBetween('-6 months', 'now');
                
                // Simulate stock conditions:
                // 20% chance depleted, 30% partially sold, 50% untouched
                $initialStock = rand(10, 100);
                $condition = rand(1, 100);
                
                if ($condition <= 20) {
                    $currentStock = 0; // Depleted
                } elseif ($condition <= 50) {
                    $currentStock = rand(1, $initialStock - 1); // Partial
                } else {
                    $currentStock = $initialStock; // Untouched
                }

                Batch::create([
                    'id' => (string) Str::uuid(),
                    'batch_code' => 'BCH-' . $createdAt->format('Ymd') . '-' . strtoupper(Str::random(5)),
                    'current_stock' => $currentStock,
                    'initial_stock' => $initialStock,
                    'purchase_price' => $purchasePrice,
                    'is_active' => true,
                    'product_brand_id' => $pb->id,
                    'created_by' => $adminUserId,
                    'created_at' => $createdAt,
                ]);
            }

            // After generating batches for this product brand, recalculate its WAC (Weighted Average Cost)
            // This mirrors real-world business logic and ensures Data Integrity.
            $pb->recalculateWAC();
        }
    }
}
