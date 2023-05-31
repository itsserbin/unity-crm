<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
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
