<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderCost extends Model
{
    protected $fillable = [
        'title',
        'sum',
        'comment',
        'order_id'
    ];
}
