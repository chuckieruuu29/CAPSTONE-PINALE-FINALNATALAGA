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
        Schema::create('product_raw_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('raw_material_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity_required', 10, 4); // Quantity needed per unit of product
            $table->string('unit_of_measure');
            $table->decimal('waste_factor', 5, 4)->default(0); // Percentage (0.05 = 5%)
            $table->text('usage_notes')->nullable();
            $table->enum('criticality', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->timestamps();
            
            // Ensure unique combination of product and raw material
            $table->unique(['product_id', 'raw_material_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_raw_materials');
    }
};
