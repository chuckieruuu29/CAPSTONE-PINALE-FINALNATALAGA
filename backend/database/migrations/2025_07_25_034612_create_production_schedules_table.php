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
        Schema::create('production_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_batch_id')->constrained()->onDelete('cascade');
            $table->string('schedule_name');
            $table->date('scheduled_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('planned_quantity');
            $table->string('work_station')->nullable();
            $table->string('assigned_worker')->nullable();
            $table->enum('shift', ['morning', 'afternoon', 'night'])->default('morning');
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'delayed', 'cancelled'])->default('scheduled');
            $table->decimal('completion_percentage', 5, 2)->default(0);
            $table->text('notes')->nullable();
            $table->json('required_materials')->nullable(); // Materials needed for this schedule
            $table->json('completion_log')->nullable(); // Track hourly/checkpoint progress
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_schedules');
    }
};
