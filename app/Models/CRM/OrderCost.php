<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CRM\OrderCost
 *
 * @method static \Illuminate\Database\Eloquent\Builder|OrderCost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderCost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderCost query()
 * @mixin \Eloquent
 * @mixin IdeHelperOrderCost
 */
class OrderCost extends Model
{
    protected $fillable = [
        'title',
        'sum',
        'comment',
        'order_id'
    ];
}
