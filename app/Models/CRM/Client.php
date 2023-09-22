<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\CRM\Client
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CRM\ClientDeliveryAddress> $addresses
 * @property-read int|null $addresses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CRM\Order> $orders
 * @property-read int|null $orders_count
 * @method static \Illuminate\Database\Eloquent\Builder|Client newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Client newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Client query()
 * @method static \Database\Factories\CRM\ClientFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 * @mixin IdeHelperClient
 */
class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'phones',
        'emails',
        'full_name',
        'comment',
        'number_of_orders',
        'number_of_success_orders',
        'success_average_check',
        'average_check',
        'success_general_check',
        'general_check',
        'last_order_created_at',
        'success_last_order_created_at',
    ];

    protected $casts = [
        'phones' => 'array',
        'emails' => 'array',
    ];

    final public function addresses(): HasMany
    {
        return $this->hasMany(ClientDeliveryAddress::class);
    }

    final public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
