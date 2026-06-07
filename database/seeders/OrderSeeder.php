<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\ProductBrand;
use App\Models\Service;
use App\Models\Batch;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Generates massive historical order data to test performance and COGS algorithms.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        
        $customers = User::where('role', 'member')->get();
        $employees = User::where('role', 'employee')->get();
        $productBrands = ProductBrand::all();
        $services = Service::all();
        
        // --- 1. Generate Historical Orders (500 Completed) ---
        $this->generateOrders(500, 'completed', '-6 months', '-1 days', $faker, $customers, $employees, $productBrands, $services);

        // --- 2. Generate Today's Queue (10 Pending, 5 Processing) ---
        $this->generateOrders(10, 'pending', 'today', 'now', $faker, $customers, null, $productBrands, $services);
        $this->generateOrders(5, 'processing', 'today', 'now', $faker, $customers, $employees, $productBrands, $services);
    }

    /**
     * Helper to generate orders with realistic items and COGS.
     */
    private function generateOrders($count, $status, $startDate, $endDate, $faker, $customers, $employees, $productBrands, $services)
    {
        $orders = [];
        $orderItems = [];

        for ($i = 0; $i < $count; $i++) {
            $orderId = (string) Str::uuid();
            $createdAt = $faker->dateTimeBetween($startDate, $endDate);
            $customer = $customers->random();
            $employee = $employees ? $employees->random() : null;

            // Generate 1 to 4 items per order
            $numItems = rand(1, 4);
            $totalPrice = 0;
            
            for ($j = 0; $j < $numItems; $j++) {
                $isProduct = rand(0, 1) == 0;
                $itemId = (string) Str::uuid();
                $qty = rand(1, 10);
                $cogs = 0;
                $subtotal = 0;
                $pricePerUnit = 0;
                
                $pbId = null;
                $srvId = null;

                if ($isProduct) {
                    $pb = $productBrands->random();
                    $pbId = $pb->id;
                    $pricePerUnit = $pb->selling_price;
                    $subtotal = $pricePerUnit * $qty;
                    
                    // Simulate Realistic COGS for completed historical orders
                    // Even if not completed, we'll populate realistic mock data
                    if ($status === 'completed') {
                        // Find batches that existed before this order
                        $batches = Batch::where('product_brand_id', $pb->id)
                                        ->where('created_at', '<=', $createdAt)
                                        ->get();
                        
                        if ($batches->isNotEmpty()) {
                            // Average purchase price of those batches * qty
                            $avgCost = $batches->avg('purchase_price');
                            $cogs = $avgCost * $qty;
                        } else {
                            // Fallback if no batch existed before order date
                            $cogs = ($pb->selling_price * 0.7) * $qty; 
                        }
                    }

                } else {
                    $srv = $services->random();
                    $srvId = $srv->id;
                    $pricePerUnit = $srv->piece_price;
                    $subtotal = $pricePerUnit * $qty;
                    // COGS for services is 0
                }

                $totalPrice += $subtotal;

                $orderItems[] = [
                    'id' => $itemId,
                    'order_id' => $orderId,
                    'product_brand_id' => $pbId,
                    'service_id' => $srvId,
                    'quantity' => $qty,
                    'price_per_unit' => $pricePerUnit,
                    'subtotal_price' => $subtotal,
                    'cogs' => $cogs,
                    'note' => $faker->optional(0.3)->sentence(),
                ];
            }

            // Create Order
            $orderNumber = 'ORD-' . strtoupper(Str::random(6)) . '-' . $createdAt->format('Ymd');
            $paidAt = $status === 'completed' ? (clone $createdAt)->modify('+' . rand(1, 60) . ' minutes') : null;
            $completedAt = $status === 'completed' ? (clone $paidAt)->modify('+' . rand(1, 120) . ' minutes') : null;

            $orders[] = [
                'id' => $orderId,
                'order_number' => $orderNumber,
                'queue_number' => rand(1, 100),
                'status' => $status,
                'total_price' => $totalPrice,
                'user_id' => $customer->id,
                'employee_id' => $employee ? $employee->id : null,
                'note' => $faker->optional(0.2)->sentence(),
                'paid_at' => $paidAt,
                'completed_at' => $completedAt,
                'created_at' => $createdAt,
                'updated_at' => $status === 'completed' ? $completedAt : $createdAt,
            ];
        }

        // Insert in chunks to prevent memory exhaustion
        foreach (array_chunk($orders, 100) as $chunk) {
            Order::insert($chunk);
        }
        foreach (array_chunk($orderItems, 300) as $chunk) {
            OrderItem::insert($chunk);
        }
    }
}
