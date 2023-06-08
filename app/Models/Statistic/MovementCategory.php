<?php

namespace App\Models\Statistic;

use Illuminate\Database\Eloquent\Model;

class MovementCategory extends Model
{
    protected $fillable = [
        'title',
        'type',
        'slug'
    ];
}
