<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = ['code', 'type', 'value', 'min_order', 'max_uses', 'used_count', 'expires_at', 'active'];

    protected $casts = ['expires_at' => 'datetime'];

    public function isValid(int $orderTotal): bool
    {
        if (!$this->active) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        if ($this->max_uses > 0 && $this->used_count >= $this->max_uses) return false;
        if ($orderTotal < $this->min_order) return false;
        return true;
    }

    public function discountAmount(int $orderTotal): int
    {
        if ($this->type === 'percent') {
            return (int) round($orderTotal * $this->value / 100);
        }
        return min($this->value, $orderTotal);
    }
}
