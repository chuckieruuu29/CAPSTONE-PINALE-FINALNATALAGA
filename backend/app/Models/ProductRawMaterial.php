<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductRawMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'raw_material_id',
        'quantity_required',
        'unit_of_measure',
        'waste_factor',
        'usage_notes',
        'criticality'
    ];

    protected $casts = [
        'quantity_required' => 'decimal:4',
        'waste_factor' => 'decimal:4',
    ];

    // Relationships
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function rawMaterial(): BelongsTo
    {
        return $this->belongsTo(RawMaterial::class);
    }

    // Scopes
    public function scopeByCriticality($query, $level)
    {
        return $query->where('criticality', $level);
    }

    public function scopeCritical($query)
    {
        return $query->where('criticality', 'critical');
    }

    public function scopeHigh($query)
    {
        return $query->where('criticality', 'high');
    }

    // Accessors & Mutators
    public function getQuantityWithWasteAttribute()
    {
        return $this->quantity_required * (1 + $this->waste_factor);
    }

    public function getWastePercentageAttribute()
    {
        return $this->waste_factor * 100;
    }

    public function getCostPerUnitAttribute()
    {
        return $this->quantity_with_waste * $this->rawMaterial->unit_cost;
    }

    // Business Methods
    public function calculateRequiredQuantity($productQuantity = 1)
    {
        return $this->quantity_with_waste * $productQuantity;
    }

    public function calculateCost($productQuantity = 1)
    {
        return $this->calculateRequiredQuantity($productQuantity) * $this->rawMaterial->unit_cost;
    }

    public function isAvailable($productQuantity = 1)
    {
        $requiredQty = $this->calculateRequiredQuantity($productQuantity);
        return $this->rawMaterial->current_stock >= $requiredQty;
    }

    public function getShortage($productQuantity = 1)
    {
        $requiredQty = $this->calculateRequiredQuantity($productQuantity);
        $available = $this->rawMaterial->current_stock;
        
        return max(0, $requiredQty - $available);
    }

    public function updateWasteFactor($newFactor, $reason = null)
    {
        $oldFactor = $this->waste_factor;
        $this->waste_factor = $newFactor;
        $this->save();

        // Log the change
        \Log::info("Waste factor updated for Product {$this->product->name} - Material {$this->rawMaterial->name}", [
            'old_factor' => $oldFactor,
            'new_factor' => $newFactor,
            'reason' => $reason,
            'updated_by' => auth()->user()->name ?? 'System'
        ]);

        return $this;
    }

    public function updateQuantity($newQuantity, $reason = null)
    {
        $oldQuantity = $this->quantity_required;
        $this->quantity_required = $newQuantity;
        $this->save();

        // Log the change
        \Log::info("Required quantity updated for Product {$this->product->name} - Material {$this->rawMaterial->name}", [
            'old_quantity' => $oldQuantity,
            'new_quantity' => $newQuantity,
            'unit' => $this->unit_of_measure,
            'reason' => $reason,
            'updated_by' => auth()->user()->name ?? 'System'
        ]);

        return $this;
    }

    public function getCriticalityColor()
    {
        return match($this->criticality) {
            'critical' => '#dc3545', // Red
            'high' => '#fd7e14',      // Orange
            'medium' => '#ffc107',    // Yellow
            'low' => '#28a745',       // Green
            default => '#6c757d'      // Gray
        };
    }

    public function getCriticalityPriority()
    {
        return match($this->criticality) {
            'critical' => 4,
            'high' => 3,
            'medium' => 2,
            'low' => 1,
            default => 0
        };
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($productRawMaterial) {
            // Set default unit of measure from raw material if not provided
            if (!$productRawMaterial->unit_of_measure && $productRawMaterial->rawMaterial) {
                $productRawMaterial->unit_of_measure = $productRawMaterial->rawMaterial->unit_of_measure;
            }
        });

        static::saved(function ($productRawMaterial) {
            // Update product cost price when BOM changes
            if ($productRawMaterial->product) {
                $productRawMaterial->product->updateCostPrice();
            }
        });
    }
}
