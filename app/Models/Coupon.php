<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_amount',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'expires_at' => 'date',
        'is_active' => 'boolean',
    ];

    public function isValid(?float $orderTotal = null): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if ($orderTotal !== null && $this->min_order_amount && $orderTotal < $this->min_order_amount) {
            return false;
        }

        return true;
    }

    public function calculateDiscount(float $total): float
    {
        if ($this->type === 'percent') {
            return ($total * $this->value) / 100;
        }

        return min($this->value, $total);
    }
}
