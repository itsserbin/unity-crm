<?php

namespace App\Models\CRM;

use App\Models\Catalog\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\CRM\OrderItem
 *
 * @property-read Image|null $preview
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem query()
 * @mixin \Eloquent
 * @mixin IdeHelperOrderItem
 */
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
