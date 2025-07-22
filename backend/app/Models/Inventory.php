<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_type',
        'item_id',
        'current_stock',
        'available_stock',
        'reserved_stock',
        'incoming_stock',
        'average_cost',
        'last_movement_date',
        'location',
        'notes'
    ];

    protected $casts = [
        'average_cost' => 'decimal:4',
        'last_movement_date' => 'date',
    ];

    // Relationships
    public function item(): MorphTo
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeProducts($query)
    {
        return $query->where('item_type', 'product');
    }

    public function scopeRawMaterials($query)
    {
        return $query->where('item_type', 'raw_material');
    }

    public function scopeLowStock($query)
    {
        return $query->where('current_stock', '<=', 10); // Basic low stock threshold
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('current_stock', '<=', 0);
    }

    public function scopeByLocation($query, $location)
    {
        return $query->where('location', $location);
    }

    // Accessors & Mutators
    public function getStockStatusAttribute()
    {
        if ($this->current_stock <= 0) return 'out_of_stock';
        if ($this->current_stock <= 10) return 'low_stock';
        if ($this->current_stock <= 50) return 'medium_stock';
        return 'good_stock';
    }

    public function getStockValueAttribute()
    {
        return $this->current_stock * $this->average_cost;
    }

    public function getReservationPercentageAttribute()
    {
        if ($this->current_stock <= 0) return 0;
        return ($this->reserved_stock / $this->current_stock) * 100;
    }

    // Business Methods
    public function adjustStock($quantity, $type = 'manual', $notes = null, $updateCost = null)
    {
        $oldStock = $this->current_stock;
        $this->current_stock += $quantity;
        
        // Update available stock
        $this->available_stock = $this->current_stock - $this->reserved_stock;
        
        // Update average cost if provided (for incoming stock)
        if ($updateCost && $quantity > 0) {
            $totalValue = ($oldStock * $this->average_cost) + ($quantity * $updateCost);
            $totalQuantity = $oldStock + $quantity;
            $this->average_cost = $totalQuantity > 0 ? $totalValue / $totalQuantity : $updateCost;
        }
        
        $this->last_movement_date = now();
        if ($notes) {
            $this->notes = $notes;
        }
        
        $this->save();

        // Log the movement
        $this->logStockMovement($quantity, $type, $notes, $oldStock, $this->current_stock);

        return $this;
    }

    public function reserveStock($quantity)
    {
        if ($this->available_stock < $quantity) {
            throw new \Exception("Insufficient available stock. Available: {$this->available_stock}, Requested: {$quantity}");
        }

        $this->reserved_stock += $quantity;
        $this->available_stock -= $quantity;
        $this->save();

        $this->logStockMovement(-$quantity, 'reservation', "Reserved {$quantity} units");

        return $this;
    }

    public function releaseReservation($quantity)
    {
        if ($this->reserved_stock < $quantity) {
            throw new \Exception("Cannot release more than reserved. Reserved: {$this->reserved_stock}, Requested: {$quantity}");
        }

        $this->reserved_stock -= $quantity;
        $this->available_stock += $quantity;
        $this->save();

        $this->logStockMovement($quantity, 'release_reservation', "Released {$quantity} units from reservation");

        return $this;
    }

    public function fulfillReservation($quantity)
    {
        if ($this->reserved_stock < $quantity) {
            throw new \Exception("Cannot fulfill more than reserved. Reserved: {$this->reserved_stock}, Requested: {$quantity}");
        }

        $this->reserved_stock -= $quantity;
        $this->current_stock -= $quantity;
        $this->save();

        $this->logStockMovement(-$quantity, 'fulfillment', "Fulfilled {$quantity} units from reservation");

        return $this;
    }

    public function receiveStock($quantity, $unitCost = null, $notes = null)
    {
        $this->adjustStock($quantity, 'receipt', $notes, $unitCost);
        
        // Update incoming stock if this was expected
        if ($this->incoming_stock >= $quantity) {
            $this->incoming_stock -= $quantity;
            $this->save();
        }

        return $this;
    }

    public function isLowStock()
    {
        // Get the item's minimum stock level if available
        $minLevel = 10; // Default threshold
        
        if ($this->item_type === 'product' && $this->item) {
            $minLevel = $this->item->min_stock_level ?? 10;
        } elseif ($this->item_type === 'raw_material' && $this->item) {
            $minLevel = $this->item->min_stock_level ?? 10;
        }

        return $this->current_stock <= $minLevel;
    }

    public function needsReorder()
    {
        // Get the item's reorder point if available
        $reorderPoint = 5; // Default threshold
        
        if ($this->item_type === 'raw_material' && $this->item) {
            $reorderPoint = $this->item->reorder_point ?? 5;
        }

        return $this->current_stock <= $reorderPoint;
    }

    protected function logStockMovement($quantity, $type, $notes = null, $oldStock = null, $newStock = null)
    {
        \Log::info("Stock movement for {$this->item_type} ID {$this->item_id}", [
            'inventory_id' => $this->id,
            'type' => $type,
            'quantity' => $quantity,
            'old_stock' => $oldStock ?? $this->current_stock,
            'new_stock' => $newStock ?? $this->current_stock,
            'notes' => $notes,
            'timestamp' => now()
        ]);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($inventory) {
            if ($inventory->available_stock === null) {
                $inventory->available_stock = $inventory->current_stock - ($inventory->reserved_stock ?? 0);
            }
        });

        static::saving(function ($inventory) {
            // Ensure available stock is never negative
            $inventory->available_stock = max(0, $inventory->current_stock - $inventory->reserved_stock);
        });
    }
}
