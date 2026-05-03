<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('batch_code', 50)->unique();
            $table->integer('current_stock')->default(0);
            $table->integer('initial_stock')->default(0);
            $table->decimal('purchase_price', 15, 0);
            $table->boolean('is_active')->default(true);
            $table->foreignUuid('product_brand_id')->constrained('product_brands')->restrictOnDelete();
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};
