<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
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
