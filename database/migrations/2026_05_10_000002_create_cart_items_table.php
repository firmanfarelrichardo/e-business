<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('cart_id')->constrained('carts')->cascadeOnDelete();
            
            // Nullable foreign keys to match order_items approach
            $table->foreignUuid('product_brand_id')->nullable()->constrained('product_brands')->cascadeOnDelete();
            $table->foreignUuid('service_id')->nullable()->constrained('services')->cascadeOnDelete();
            
            $table->integer('quantity')->default(1);
            $table->text('note')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
