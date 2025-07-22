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
        Schema::create('raw_materials', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category');
            $table->enum('type', ['wood', 'hardware', 'finish', 'adhesive', 'other'])->default('wood');
            $table->string('unit_of_measure'); // pieces, kg, liters, meters, etc.
            $table->decimal('unit_cost', 15, 2);
            $table->integer('current_stock')->default(0);
            $table->integer('min_stock_level');
            $table->integer('max_stock_level');
            $table->integer('reorder_point');
            $table->integer('reorder_quantity');
            $table->string('supplier_name')->nullable();
            $table->string('supplier_contact')->nullable();
            $table->integer('lead_time_days')->default(0);
            $table->decimal('storage_cost_per_unit', 10, 4)->default(0);
            $table->string('storage_location')->nullable();
            $table->date('last_restock_date')->nullable();
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
        Schema::dropIfExists('raw_materials');
    }
};
