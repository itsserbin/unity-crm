<?php

namespace App\Models\CRM;

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
