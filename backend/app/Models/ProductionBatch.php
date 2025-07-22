<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductionBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_number',
        'product_id',
        'order_item_id',
        'planned_quantity',
        'actual_quantity',
        'completed_quantity',
        'rejected_quantity',
        'status',
        'planned_start_date',
        'planned_end_date',
        'actual_start_date',
        'actual_end_date',
        'estimated_hours',
        'actual_hours',
        'efficiency_percentage',
        'supervisor',
        'production_notes',
        'quality_notes',
        'material_usage'
    ];

    protected $casts = [
        'planned_start_date' => 'date',
        'planned_end_date' => 'date',
        'actual_start_date' => 'date',
        'actual_end_date' => 'date',
        'estimated_hours' => 'decimal:2',
        'actual_hours' => 'decimal:2',
        'efficiency_percentage' => 'decimal:2',
        'material_usage' => 'array',
    ];

    // Relationships
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function productionSchedules(): HasMany
    {
        return $this->hasMany(ProductionSchedule::class);
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePlanned($query)
    {
        return $query->where('status', 'planned');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeOverdue($query)
    {
        return $query->where('planned_end_date', '<', now())
                    ->whereNotIn('status', ['completed', 'cancelled']);
    }

    public function scopeBySupervisor($query, $supervisor)
    {
        return $query->where('supervisor', $supervisor);
    }

    // Accessors & Mutators
    public function getIsOverdueAttribute()
    {
        return $this->planned_end_date && 
               $this->planned_end_date->isPast() && 
               !in_array($this->status, ['completed', 'cancelled']);
    }

    public function getCompletionPercentageAttribute()
    {
        if ($this->planned_quantity <= 0) return 0;
        return ($this->completed_quantity / $this->planned_quantity) * 100;
    }

    public function getQualityRateAttribute()
    {
        $totalProduced = $this->completed_quantity + $this->rejected_quantity;
        if ($totalProduced <= 0) return 0;
        return ($this->completed_quantity / $totalProduced) * 100;
    }

    public function getVariancePercentageAttribute()
    {
        if ($this->planned_quantity <= 0) return 0;
        return (($this->actual_quantity - $this->planned_quantity) / $this->planned_quantity) * 100;
    }

    public function getTimeVarianceAttribute()
    {
        if ($this->estimated_hours <= 0) return 0;
        return $this->actual_hours - $this->estimated_hours;
    }

    // Business Methods
    public function generateBatchNumber()
    {
        $product = $this->product;
        $date = now()->format('Ymd');
        $lastBatch = static::where('batch_number', 'like', "BATCH-{$product->sku}-{$date}%")
                          ->orderBy('batch_number', 'desc')
                          ->first();

        if ($lastBatch) {
            $lastNumber = intval(substr($lastBatch->batch_number, -3));
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return "BATCH-{$product->sku}-{$date}-{$newNumber}";
    }

    public function startProduction()
    {
        if ($this->status !== 'planned') {
            throw new \Exception('Batch must be in planned status to start production');
        }

        // Check material availability
        if (!$this->checkMaterialAvailability()) {
            throw new \Exception('Insufficient materials to start production');
        }

        $this->status = 'in_progress';
        $this->actual_start_date = now();
        $this->save();

        // Reserve materials
        $this->reserveMaterials();

        return $this;
    }

    public function pauseProduction($reason = null)
    {
        if ($this->status !== 'in_progress') {
            throw new \Exception('Can only pause batches in progress');
        }

        $this->status = 'paused';
        if ($reason) {
            $this->production_notes = ($this->production_notes ? $this->production_notes . "\n" : '') . 
                                    "Paused: {$reason} at " . now()->format('Y-m-d H:i:s');
        }
        $this->save();

        return $this;
    }

    public function resumeProduction()
    {
        if ($this->status !== 'paused') {
            throw new \Exception('Can only resume paused batches');
        }

        $this->status = 'in_progress';
        $this->production_notes = ($this->production_notes ? $this->production_notes . "\n" : '') . 
                                "Resumed at " . now()->format('Y-m-d H:i:s');
        $this->save();

        return $this;
    }

    public function completeProduction()
    {
        if (!in_array($this->status, ['in_progress', 'quality_check'])) {
            throw new \Exception('Can only complete batches in progress or quality check');
        }

        $this->status = 'completed';
        $this->actual_end_date = now();
        $this->calculateEfficiency();
        $this->save();

        // Update inventory
        $this->updateInventory();

        // Complete related order item if applicable
        if ($this->orderItem && $this->completed_quantity >= $this->orderItem->quantity) {
            $this->orderItem->completeProduction();
        }

        return $this;
    }

    public function recordProduction($completedQty, $rejectedQty = 0, $notes = null)
    {
        if ($this->status !== 'in_progress') {
            throw new \Exception('Can only record production for batches in progress');
        }

        $this->completed_quantity += $completedQty;
        $this->rejected_quantity += $rejectedQty;
        $this->actual_quantity = $this->completed_quantity + $this->rejected_quantity;

        if ($notes) {
            $this->production_notes = ($this->production_notes ? $this->production_notes . "\n" : '') . 
                                    now()->format('Y-m-d H:i:s') . ": {$notes}";
        }

        // Auto-complete if we've reached planned quantity
        if ($this->completed_quantity >= $this->planned_quantity) {
            $this->status = 'quality_check';
        }

        $this->save();

        return $this;
    }

    public function checkMaterialAvailability()
    {
        $requiredMaterials = $this->product->calculateRequiredMaterials($this->planned_quantity);
        
        foreach ($requiredMaterials as $material) {
            $rawMaterial = RawMaterial::find($material['material_id']);
            if (!$rawMaterial || $rawMaterial->current_stock < $material['with_waste']) {
                return false;
            }
        }

        return true;
    }

    public function reserveMaterials()
    {
        $requiredMaterials = $this->product->calculateRequiredMaterials($this->planned_quantity);
        $materialUsage = [];

        foreach ($requiredMaterials as $material) {
            $rawMaterial = RawMaterial::find($material['material_id']);
            if ($rawMaterial && $rawMaterial->inventory) {
                $rawMaterial->inventory->reserveStock($material['with_waste']);
                
                $materialUsage[] = [
                    'material_id' => $material['material_id'],
                    'material_name' => $material['material_name'],
                    'reserved_quantity' => $material['with_waste'],
                    'unit' => $material['unit'],
                    'reserved_at' => now()
                ];
            }
        }

        $this->material_usage = $materialUsage;
        $this->save();
    }

    protected function calculateEfficiency()
    {
        if ($this->estimated_hours > 0 && $this->actual_hours > 0) {
            $this->efficiency_percentage = ($this->estimated_hours / $this->actual_hours) * 100;
        }

        // Factor in quality rate
        if ($this->planned_quantity > 0) {
            $quantityEfficiency = ($this->completed_quantity / $this->planned_quantity) * 100;
            $this->efficiency_percentage = ($this->efficiency_percentage + $quantityEfficiency) / 2;
        }
    }

    protected function updateInventory()
    {
        if ($this->completed_quantity > 0) {
            $inventory = $this->product->inventory;
            if ($inventory) {
                $inventory->adjustStock(
                    $this->completed_quantity, 
                    'production', 
                    "Production batch {$this->batch_number} completed"
                );
            }
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($batch) {
            if (!$batch->batch_number) {
                $batch->batch_number = $batch->generateBatchNumber();
            }
            if (!$batch->estimated_hours && $batch->product) {
                $batch->estimated_hours = $batch->product->production_time_hours * $batch->planned_quantity;
            }
        });
    }
}
