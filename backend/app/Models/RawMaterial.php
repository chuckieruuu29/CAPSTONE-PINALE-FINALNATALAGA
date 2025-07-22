<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RawMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'name',
        'description',
        'category',
        'type',
        'unit_of_measure',
        'unit_cost',
        'current_stock',
        'min_stock_level',
        'max_stock_level',
        'reorder_point',
        'reorder_quantity',
        'supplier_name',
        'supplier_contact',
        'lead_time_days',
        'storage_cost_per_unit',
        'storage_location',
        'last_restock_date',
        'status',
        'notes'
    ];

    protected $casts = [
        'unit_cost' => 'decimal:2',
        'storage_cost_per_unit' => 'decimal:4',
        'last_restock_date' => 'date',
    ];

    // Relationships
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_raw_materials')
            ->withPivot('quantity_required', 'unit_of_measure', 'waste_factor', 'usage_notes', 'criticality')
            ->withTimestamps();
    }

    public function inventory(): MorphOne
    {
        return $this->morphOne(Inventory::class, 'item');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('current_stock <= reorder_point');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeBySupplier($query, $supplier)
    {
        return $query->where('supplier_name', 'like', "%{$supplier}%");
    }

    // Accessors & Mutators
    public function getStockStatusAttribute()
    {
        if ($this->current_stock <= 0) return 'out_of_stock';
        if ($this->current_stock <= $this->reorder_point) return 'low_stock';
        if ($this->current_stock <= $this->min_stock_level) return 'below_minimum';
        if ($this->current_stock >= $this->max_stock_level) return 'overstock';
        return 'normal';
    }

    public function getStockValueAttribute()
    {
        return $this->current_stock * $this->unit_cost;
    }

    public function getDaysOfStockAttribute()
    {
        // Calculate based on average consumption (simplified)
        $avgDailyUsage = $this->getAverageDailyUsage();
        return $avgDailyUsage > 0 ? intval($this->current_stock / $avgDailyUsage) : 999;
    }

    // Business Methods
    public function needsReorder()
    {
        return $this->current_stock <= $this->reorder_point;
    }

    public function isLowStock()
    {
        return $this->current_stock <= $this->min_stock_level;
    }

    public function isOutOfStock()
    {
        return $this->current_stock <= 0;
    }

    public function getReorderSuggestion()
    {
        if (!$this->needsReorder()) {
            return null;
        }

        $suggestedQuantity = max(
            $this->reorder_quantity,
            $this->max_stock_level - $this->current_stock
        );

        return [
            'material_id' => $this->id,
            'material_name' => $this->name,
            'current_stock' => $this->current_stock,
            'reorder_point' => $this->reorder_point,
            'suggested_quantity' => $suggestedQuantity,
            'estimated_cost' => $suggestedQuantity * $this->unit_cost,
            'supplier' => $this->supplier_name,
            'lead_time_days' => $this->lead_time_days,
            'urgency' => $this->isOutOfStock() ? 'urgent' : 'normal'
        ];
    }

    public function getAverageDailyUsage()
    {
        // This would typically calculate from production history
        // For now, return a simplified calculation
        return 5; // Default 5 units per day
    }

    public function updateStock($quantity, $type = 'manual', $notes = null)
    {
        $oldStock = $this->current_stock;
        $this->current_stock += $quantity;
        
        if ($quantity > 0) {
            $this->last_restock_date = now();
        }
        
        $this->save();

        // Log the stock movement (you might want to create a StockMovement model)
        \Log::info("Stock updated for {$this->name}: {$oldStock} -> {$this->current_stock} (Change: {$quantity})", [
            'material_id' => $this->id,
            'type' => $type,
            'notes' => $notes
        ]);

        return $this;
    }

    public function getForecastedStockOut()
    {
        $avgDailyUsage = $this->getAverageDailyUsage();
        if ($avgDailyUsage <= 0) return null;

        $daysRemaining = intval($this->current_stock / $avgDailyUsage);
        return now()->addDays($daysRemaining);
    }
}
