<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');                                   // e.g. "Weekend Flash Sale"
            $table->string('slug')->unique();

            $table->enum('type', ['percentage', 'fixed']);
            $table->decimal('value', 12, 2);                          // 20 for 20%, or 200.00 for ৳200 off

            $table->timestamp('starts_at')->nullable();                // null = active immediately
            $table->timestamp('ends_at')->nullable();                  // null = no expiry
            $table->boolean('is_active')->default(true);               // manual on/off switch, independent of dates

            $table->text('description')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
