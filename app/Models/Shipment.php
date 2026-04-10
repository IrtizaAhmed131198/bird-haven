<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'tracking_number',
        'stage',
        'temperature',
        'oxygen',
        'estimated_delivery',
        'hatchery_date',
        'health_date',
        'flight_date',
        'local_date',
        'delivery_date',
    ];

    protected function casts(): array
    {
        return [
            'estimated_delivery' => 'date',
            'hatchery_date'      => 'date',
            'health_date'        => 'date',
            'flight_date'        => 'date',
            'local_date'         => 'date',
            'delivery_date'      => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Shipment $shipment) {
            if (empty($shipment->tracking_number)) {
                $shipment->tracking_number = 'AVY-TRK-' . strtoupper(substr(uniqid(), -8));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
