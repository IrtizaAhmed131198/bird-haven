<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'status_message',
        'payment_method',
        'payment_status',
        'transaction_id',
        'subtotal',
        'shipping',
        'tax',
        'total',
        'shipping_name',
        'shipping_address',
        'shipping_address2',
        'shipping_city',
        'shipping_postal',
        'estimated_delivery',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'subtotal'           => 'decimal:2',
            'shipping'           => 'decimal:2',
            'tax'                => 'decimal:2',
            'total'              => 'decimal:2',
            'estimated_delivery' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (empty($order->order_number)) {
                $order->order_number = 'AVY-' . strtoupper(substr(uniqid(), -8));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shipment(): HasOne
    {
        return $this->hasOne(Shipment::class);
    }
}
