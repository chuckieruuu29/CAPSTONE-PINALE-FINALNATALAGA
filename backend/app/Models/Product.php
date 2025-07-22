<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'name',
        'description',
        'category',
        'type',
        'selling_price',
        'cost_price',
        'weight',
        'dimensions',
        'wood_type',
        'finish',
        'production_time_hours',
        'min_stock_level',
        'max_stock_level',
        'image_url',
        'status',
        'notes'
    ];

    protected $casts = [
        'selling_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'weight' => 'decimal:2',
    ];

    // Relationships
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function productionBatches(): HasMany
    {
        return $this->hasMany(ProductionBatch::class);
    }

    public function rawMaterials(): BelongsToMany
    {
        return $this->belongsToMany(RawMaterial::class, 'product_raw_materials')
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

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeLowStock($query)
    {
        return $query->whereHas('inventory', function($q) {
            $q->whereRaw('current_stock <= products.min_stock_level');
        });
    }

    // Accessors & Mutators
    public function getProfitMarginAttribute()
    {
        if ($this->cost_price == 0) return 0;
        return (($this->selling_price - $this->cost_price) / $this->selling_price) * 100;
    }

    public function getCurrentStockAttribute()
    {
        return $this->inventory ? $this->inventory->current_stock : 0;
    }

    public function getAvailableStockAttribute()
    {
        return $this->inventory ? $this->inventory->available_stock : 0;
    }

    // Business Methods
    public function calculateRequiredMaterials($quantity = 1)
    {
        $materials = [];
        foreach ($this->rawMaterials as $material) {
            $required = $material->pivot->quantity_required * $quantity;
            $withWaste = $required * (1 + $material->pivot->waste_factor);
            
            $materials[] = [
                'material_id' => $material->id,
                'material_name' => $material->name,
                'required_quantity' => $required,
                'with_waste' => $withWaste,
                'unit' => $material->pivot->unit_of_measure,
                'criticality' => $material->pivot->criticality
            ];
        }
        return $materials;
    }

    public function canProduce($quantity = 1)
    {
        $requiredMaterials = $this->calculateRequiredMaterials($quantity);
        
        foreach ($requiredMaterials as $material) {
            $rawMaterial = RawMaterial::find($material['material_id']);
            if (!$rawMaterial || $rawMaterial->current_stock < $material['with_waste']) {
                return false;
            }
        }
        return true;
    }

    public function getEstimatedProductionCost($quantity = 1)
    {
        $materialCost = 0;
        $requiredMaterials = $this->calculateRequiredMaterials($quantity);
        
        foreach ($requiredMaterials as $material) {
            $rawMaterial = RawMaterial::find($material['material_id']);
            $materialCost += $rawMaterial->unit_cost * $material['with_waste'];
        }
        
        // Add labor cost (assuming hourly rate)
        $laborRate = 25; // $25 per hour - this could be configurable
        $laborCost = $this->production_time_hours * $laborRate * $quantity;
        
        return $materialCost + $laborCost;
    }

    public function isLowStock()
    {
        return $this->current_stock <= $this->min_stock_level;
    }

    public function needsReorder()
    {
        return $this->current_stock <= ($this->min_stock_level * 1.2); // 20% above minimum
    }

    public function updateCostPrice()
    {
        $materialCost = $this->rawMaterials->sum(function ($material) {
            return $material->pivot->quantity_with_waste * $material->unit_cost;
        });

        // Add labor cost estimation
        $laborRate = 25; // $25 per hour - configurable
        $laborCost = $this->production_time_hours * $laborRate;

        $this->cost_price = $materialCost + $laborCost;
        $this->save();

        return $this;
    }
}
