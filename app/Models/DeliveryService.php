<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryService extends Model
{
    protected $fillable = [
        'status',
        'title',
        'type',
        'api_key',
        'configuration',
    ];

    protected $casts = [
        'configuration' => 'array'
    ];

    final public function trackingCode(): HasMany
    {
        return $this->hasMany(TrackingCode::class, 'delivery_service_id');
    }
}
