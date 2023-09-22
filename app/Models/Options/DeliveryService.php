<?php

namespace App\Models\Options;

use App\Models\CRM\TrackingCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Options\DeliveryService
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, TrackingCode> $trackingCode
 * @property-read int|null $tracking_code_count
 * @method static \Illuminate\Database\Eloquent\Builder|DeliveryService newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeliveryService newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeliveryService query()
 * @mixin \Eloquent
 * @mixin IdeHelperDeliveryService
 */
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
