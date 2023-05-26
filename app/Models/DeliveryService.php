<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
