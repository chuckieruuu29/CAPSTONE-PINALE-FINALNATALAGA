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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category');
            $table->enum('type', ['furniture', 'decor', 'custom', 'other'])->default('furniture');
            $table->decimal('selling_price', 15, 2);
            $table->decimal('cost_price', 15, 2)->default(0);
            $table->decimal('weight', 8, 2)->nullable();
            $table->string('dimensions')->nullable(); // L x W x H
            $table->string('wood_type')->nullable();
            $table->string('finish')->nullable();
            $table->integer('production_time_hours')->default(0);
            $table->integer('min_stock_level')->default(0);
            $table->integer('max_stock_level')->default(0);
            $table->string('image_url')->nullable();
            $table->enum('status', ['active', 'inactive', 'discontinued'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
