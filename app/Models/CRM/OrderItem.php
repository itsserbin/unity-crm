<?php

namespace App\Models\CRM;

use App\Models\Catalog\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'additional_sale',
        'preview_id',
        'sku',
        'title',
        'comment',
        'count',
        'trade_price',
        'product_price',
        'discount',
        'sale_price',
    ];

    final public function preview(): BelongsTo
    {
        return $this->belongsTo(Image::class, 'preview_id');
    }
}
