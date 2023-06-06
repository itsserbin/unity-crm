<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'additional_sale',
        'image',
        'sku',
        'title',
        'comment',
        'count',
        'trade_price',
        'product_price',
        'discount',
        'sale_price',
    ];
}
