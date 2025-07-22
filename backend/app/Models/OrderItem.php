<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'line_total',
        'customization_details',
        'production_notes',
        'status',
        'production_start_date',
        'production_end_date',
        'production_days_estimated'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'line_total' => 'decimal:2',
        'production_start_date' => 'date',
        'production_end_date' => 'date',
    ];

    // Relationships
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function productionBatches(): HasMany
    {
        return $this->hasMany(ProductionBatch::class);
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProduction($query)
    {
        return $query->where('status', 'in_production');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Accessors & Mutators
    public function getIsCustomizedAttribute()
    {
        return !empty($this->customization_details);
    }

    public function getProductionProgressAttribute()
    {
        $statusProgress = [
            'pending' => 0,
            'in_production' => 50,
            'completed' => 100,
            'shipped' => 100
        ];

        return $statusProgress[$this->status] ?? 0;
    }

    public function getEstimatedProductionDaysAttribute()
    {
        if ($this->production_days_estimated) {
            return $this->production_days_estimated;
        }

        // Calculate based on product production time and quantity
        $baseProductionHours = $this->product->production_time_hours * $this->quantity;
        
        // Add extra time for customization
        if ($this->is_customized) {
            $baseProductionHours *= 1.5; // 50% more time for customized items
        }

        return ceil($baseProductionHours / 8); // 8 hours per day
    }

    // Business Methods
    public function calculateLineTotal()
    {
        $this->line_total = $this->quantity * $this->unit_price;
        $this->save();

        return $this;
    }

    public function canStartProduction()
    {
        return $this->status === 'pending' && 
               $this->product->canProduce($this->quantity);
    }

    public function startProduction()
    {
        if (!$this->canStartProduction()) {
            throw new \Exception('Cannot start production for this item');
        }

        $this->status = 'in_production';
        $this->production_start_date = now();
        $this->save();

        return $this;
    }

    public function completeProduction()
    {
        if ($this->status !== 'in_production') {
            throw new \Exception('Item is not in production');
        }

        $this->status = 'completed';
        $this->production_end_date = now();
        $this->save();

        // Check if all order items are completed
        $this->checkOrderCompletion();

        return $this;
    }

    public function getRequiredMaterials()
    {
        return $this->product->calculateRequiredMaterials($this->quantity);
    }

    public function getProductionCost()
    {
        return $this->product->getEstimatedProductionCost($this->quantity);
    }

    protected function checkOrderCompletion()
    {
        $order = $this->order;
        $allItemsCompleted = $order->orderItems()
            ->where('status', '!=', 'completed')
            ->count() === 0;

        if ($allItemsCompleted && $order->status === 'in_production') {
            $order->updateStatus('ready');
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($orderItem) {
            if (!$orderItem->line_total) {
                $orderItem->line_total = $orderItem->quantity * $orderItem->unit_price;
            }
        });

        static::saving(function ($orderItem) {
            $orderItem->line_total = $orderItem->quantity * $orderItem->unit_price;
        });
    }
}
