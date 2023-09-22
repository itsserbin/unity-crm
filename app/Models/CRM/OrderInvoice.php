<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CRM\OrderInvoice
 *
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInvoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInvoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInvoice query()
 * @mixin \Eloquent
 * @mixin IdeHelperOrderInvoice
 */
class OrderInvoice extends Model
{
    protected $fillable = [
        'order_id',
        'date',
        'payment_type',
        'sum',
        'comment',
        'status',
    ];
}
