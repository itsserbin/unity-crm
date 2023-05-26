<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
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
}
