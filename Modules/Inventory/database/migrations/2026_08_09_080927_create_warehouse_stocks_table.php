<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('warehouse_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->constrained('product_variants')->cascadeOnDelete();
            $table->unsignedInteger('quantity')->default(0);           // on-hand
            $table->unsignedInteger('reserved_quantity')->default(0);  // held by unfulfilled orders
            $table->unsignedInteger('reorder_level')->default(0);      // low-stock threshold
            $table->unsignedInteger('reorder_quantity')->default(0);   // suggested restock qty
            $table->timestamps();

            $table->unique(['warehouse_id', 'product_variant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_stocks');
    }
};
