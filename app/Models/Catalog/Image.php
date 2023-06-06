<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'alt',
        'data'
    ];

    protected $casts = [
        'data' => 'array'
    ];
}
