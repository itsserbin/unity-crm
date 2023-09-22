<?php

namespace App\Models\CRM;

use App\Models\Options\DeliveryService;
use App\Models\Options\Source;
use App\Models\Options\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\CRM\Order
 *
 * @property-read \App\Models\CRM\Client|null $client
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CRM\OrderCost> $costs
 * @property-read int|null $costs_count
 * @property-read DeliveryService|null $deliveryService
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CRM\OrderInvoice> $invoices
 * @property-read int|null $invoices_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CRM\OrderItem> $items
 * @property-read int|null $items_count
 * @property-read User|null $manager
 * @property-read Source|null $source
 * @property-read Status|null $status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CRM\TrackingCode> $trackingCodes
 * @property-read int|null $tracking_codes_count
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @mixin \Eloquent
 * @mixin IdeHelperOrder
 */
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
        'manager_comment',
        'client_comment',
        'total_price',
        'trade_price',
        'clear_total_price',
        'discount',
    ];

    /**
     * @return HasMany
     */
    final public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    /**
     * @return BelongsTo
     */
    final public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }

    /**
     * @return BelongsTo
     */
    final public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * @return BelongsTo
     */
    final public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * @return BelongsTo
     */
    final public function deliveryService(): BelongsTo
    {
        return $this->belongsTo(DeliveryService::class);
    }

    /**
     * @return BelongsTo
     */
    final public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany
     */
    final public function costs(): HasMany
    {
        return $this->hasMany(OrderCost::class, 'order_id');
    }

    /**
     * @return HasMany
     */
    final public function invoices(): HasMany
    {
        return $this->hasMany(OrderInvoice::class, 'order_id');
    }

    /**
     * @return HasMany
     */
    final public function trackingCodes(): HasMany
    {
        return $this->hasMany(TrackingCode::class, 'order_id');
    }
}
