<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrackingCode extends Model
{
    protected $fillable = [
        'order_id',
        'code',
        'delivery_service_id',
        'log',
        'data',
    ];

    protected $casts = [
        'log' => 'array',
        'data' => 'array'
    ];

    final public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    final public function deliveryServices(): BelongsTo
    {
        return $this->belongsTo(DeliveryService::class, 'delivery_service_id');
    }
}
