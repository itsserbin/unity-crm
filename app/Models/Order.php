<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'source_id',
        'status_id',
        'client_id',
        'manager_id',
        'delivery_service_id',
        'delivery_address',
        'tracking_code',
        'comment',
        'total_price',
        'trade_price',
        'clear_total_price',
        'discount',
    ];

    final public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    final public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }

    final public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    final public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    final public function deliveryService(): BelongsTo
    {
        return $this->belongsTo(DeliveryService::class);
    }

    final public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    final public function costs(): HasMany
    {
        return $this->hasMany(OrderCost::class, 'order_id');
    }

    final public function invoices(): HasMany
    {
        return $this->hasMany(OrderInvoice::class, 'order_id');
    }

    final public function trackingCodes(): HasMany
    {
        return $this->hasMany(TrackingCode::class, 'order_id');
    }
}
