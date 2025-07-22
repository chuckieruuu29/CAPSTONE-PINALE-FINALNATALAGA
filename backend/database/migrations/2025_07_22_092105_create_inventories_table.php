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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->enum('item_type', ['product', 'raw_material']);
            $table->unsignedBigInteger('item_id'); // Can reference products or raw_materials
            $table->integer('current_stock');
            $table->integer('available_stock'); // Current stock minus reserved
            $table->integer('reserved_stock')->default(0);
            $table->integer('incoming_stock')->default(0);
            $table->decimal('average_cost', 15, 4)->default(0);
            $table->date('last_movement_date')->nullable();
            $table->string('location')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Index for polymorphic relationship
            $table->index(['item_type', 'item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
