<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'zip_code',
        'contact_person',
        'status',
        'credit_limit',
        'notes'
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
    ];

    // Relationships
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    // Accessors & Mutators
    public function getFullAddressAttribute()
    {
        $address = $this->address;
        if ($this->city) $address .= ', ' . $this->city;
        if ($this->state) $address .= ', ' . $this->state;
        if ($this->zip_code) $address .= ' ' . $this->zip_code;
        return $address;
    }

    // Business Methods
    public function getTotalOrdersAmount()
    {
        return $this->orders()->sum('total_amount');
    }

    public function getPendingOrdersCount()
    {
        return $this->orders()->whereIn('status', ['pending', 'confirmed', 'in_production'])->count();
    }

    public function getAvailableCredit()
    {
        $pendingAmount = $this->orders()
            ->whereIn('status', ['pending', 'confirmed', 'in_production'])
            ->sum('total_amount');
        
        return $this->credit_limit - $pendingAmount;
    }

    public function canPlaceOrder($amount)
    {
        return $this->status === 'active' && $this->getAvailableCredit() >= $amount;
    }
}
