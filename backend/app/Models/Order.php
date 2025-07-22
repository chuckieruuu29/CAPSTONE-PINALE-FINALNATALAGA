<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_id',
        'order_date',
        'required_date',
        'promised_date',
        'shipped_date',
        'delivered_date',
        'status',
        'priority',
        'subtotal',
        'tax_amount',
        'shipping_cost',
        'discount_amount',
        'total_amount',
        'shipping_address',
        'billing_address',
        'shipping_method',
        'tracking_number',
        'special_instructions',
        'internal_notes',
        'created_by'
    ];

    protected $casts = [
        'order_date' => 'date',
        'required_date' => 'date',
        'promised_date' => 'date',
        'shipped_date' => 'date',
        'delivered_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    // Relationships
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProduction($query)
    {
        return $query->where('status', 'in_production');
    }

    public function scopeOverdue($query)
    {
        return $query->where('required_date', '<', now())
                    ->whereNotIn('status', ['delivered', 'cancelled']);
    }

    // Accessors & Mutators
    public function getIsOverdueAttribute()
    {
        return $this->required_date && 
               $this->required_date->isPast() && 
               !in_array($this->status, ['delivered', 'cancelled']);
    }

    public function getProgressPercentageAttribute()
    {
        $statusProgress = [
            'pending' => 10,
            'confirmed' => 25,
            'in_production' => 50,
            'ready' => 75,
            'shipped' => 90,
            'delivered' => 100,
            'cancelled' => 0,
            'on_hold' => 15
        ];

        return $statusProgress[$this->status] ?? 0;
    }

    // Business Methods
    public function calculateTotals()
    {
        $itemsTotal = $this->orderItems()->sum(\DB::raw('quantity * unit_price'));
        $this->subtotal = $itemsTotal - $this->discount_amount;
        $this->total_amount = $this->subtotal + $this->tax_amount + $this->shipping_cost;
        $this->save();

        return $this;
    }

    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'confirmed', 'on_hold']);
    }

    public function canBeShipped()
    {
        return $this->status === 'ready';
    }

    public function canBeDelivered()
    {
        return $this->status === 'shipped' && $this->tracking_number;
    }

    public function generateOrderNumber()
    {
        $year = now()->year;
        $month = now()->format('m');
        $lastOrder = static::where('order_number', 'like', "ORD-{$year}{$month}%")
                          ->orderBy('order_number', 'desc')
                          ->first();

        if ($lastOrder) {
            $lastNumber = intval(substr($lastOrder->order_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "ORD-{$year}{$month}{$newNumber}";
    }

    public function updateStatus($newStatus, $notes = null)
    {
        $oldStatus = $this->status;
        $this->status = $newStatus;

        // Set specific dates based on status
        switch ($newStatus) {
            case 'shipped':
                $this->shipped_date = now();
                break;
            case 'delivered':
                $this->delivered_date = now();
                break;
        }

        $this->save();

        // Log status change
        \Log::info("Order {$this->order_number} status changed from {$oldStatus} to {$newStatus}", [
            'order_id' => $this->id,
            'notes' => $notes
        ]);

        return $this;
    }

    public function getEstimatedCompletionDate()
    {
        $totalProductionDays = $this->orderItems()
            ->with('product')
            ->get()
            ->sum(function ($item) {
                return $item->product->production_time_hours * $item->quantity / 8; // 8 hours per day
            });

        return now()->addDays(ceil($totalProductionDays));
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (!$order->order_number) {
                $order->order_number = $order->generateOrderNumber();
            }
            if (!$order->order_date) {
                $order->order_date = now();
            }
        });
    }
}
