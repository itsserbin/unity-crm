<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Model;

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
