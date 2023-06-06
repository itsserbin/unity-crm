<?php

namespace App\Models\Statistic;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankAccountMovement extends Model
{
    protected $fillable = [
        'account_id',
        'movement_id',
        'date',
        'sum',
        'balance',
        'category_id',
        'comment',
    ];

    final public function account(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class, 'account_id');
    }

    final public function category(): BelongsTo
    {
        return $this->belongsTo(MovementCategory::class, 'category_id');
    }
}
