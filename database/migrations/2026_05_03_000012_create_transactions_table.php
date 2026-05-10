<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('transaction_code', 100)->unique();
            $table->string('transaction_status', 50)->default('pending');
            $table->string('payment_url')->nullable();
            $table->json('gateway_response')->nullable();
            $table->foreignUuid('payment_type_id')->nullable()->constrained('payment_methods')->restrictOnDelete();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('paid_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
