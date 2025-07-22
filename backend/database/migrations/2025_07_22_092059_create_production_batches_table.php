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
        Schema::create('production_batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_number')->unique();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_item_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('planned_quantity');
            $table->integer('actual_quantity')->default(0);
            $table->integer('completed_quantity')->default(0);
            $table->integer('rejected_quantity')->default(0);
            $table->enum('status', [
                'planned', 'in_progress', 'paused', 'completed', 
                'cancelled', 'quality_check'
            ])->default('planned');
            $table->date('planned_start_date');
            $table->date('planned_end_date');
            $table->date('actual_start_date')->nullable();
            $table->date('actual_end_date')->nullable();
            $table->decimal('estimated_hours', 8, 2)->default(0);
            $table->decimal('actual_hours', 8, 2)->default(0);
            $table->decimal('efficiency_percentage', 5, 2)->default(0);
            $table->string('supervisor')->nullable();
            $table->text('production_notes')->nullable();
            $table->text('quality_notes')->nullable();
            $table->json('material_usage')->nullable(); // JSON to track material consumption
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_batches');
    }
};
