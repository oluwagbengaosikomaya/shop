<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'customer_name', 'customer_email', 'customer_phone',
        'delivery_address', 'delivery_city', 'delivery_state',
        'total', 'delivery_fee', 'status', 'payment_reference',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function statusColor(): string
    {
        return match($this->status) {
            'completed'  => 'success',
            'processing' => 'primary',
            'shipped'    => 'info',
            'cancelled'  => 'danger',
            default      => 'warning',
        };
    }

    public function isCancellable(): bool
    {
        return in_array($this->status, ['pending', 'processing']);
    }
}
