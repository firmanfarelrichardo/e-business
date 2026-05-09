<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds supplier_name column to batches table.
 *
 * Tracks the supplier source for each stock batch, enabling
 * future procurement reporting and supplier performance analysis.
 * Nullable because existing batches (created via expense records)
 * may not have supplier data retroactively.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('batches', function (Blueprint $table) {
            $table->string('supplier_name', 100)->nullable()->after('purchase_price');
        });
    }

    public function down(): void
    {
        Schema::table('batches', function (Blueprint $table) {
            $table->dropColumn('supplier_name');
        });
    }
};
