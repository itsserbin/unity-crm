<?php

namespace App\Models\Statistic;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = [
        'data',
        'name'
    ];

    protected $casts = [
        'data' => 'array'
    ];
}
