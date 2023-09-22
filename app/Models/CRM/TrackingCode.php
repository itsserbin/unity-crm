<?php

namespace App\Models\CRM;

use App\Models\Options\DeliveryService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\CRM\TrackingCode
 *
 * @property-read DeliveryService|null $deliveryServices
 * @property-read \App\Models\CRM\Order|null $order
 * @method static \Illuminate\Database\Eloquent\Builder|TrackingCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrackingCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrackingCode query()
 * @mixin \Eloquent
 * @mixin IdeHelperTrackingCode
 */
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
