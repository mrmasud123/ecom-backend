<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();

            $table->decimal('price', 12, 2)->default(0);
            $table->decimal('compare_price', 12, 2)->nullable(); // "was" price for showing discounts
            $table->decimal('cost_price', 12, 2)->nullable();    // internal cost, for margin reports

            $table->boolean('track_quantity')->default(true);
            $table->unsignedInteger('quantity')->default(0);
            $table->boolean('allow_backorder')->default(false);

            $table->decimal('weight', 8, 2)->nullable();   // for shipping calculations
            $table->string('length')->nullable();
            $table->string('width')->nullable();
            $table->string('height')->nullable();

            $table->boolean('has_variants')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);

            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();

            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
