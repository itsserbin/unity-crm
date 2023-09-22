<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Finance\BankAccountMovement
 *
 * @property-read \App\Models\Finance\Account|null $account
 * @property-read \App\Models\Finance\MovementCategory|null $category
 * @method static \Illuminate\Database\Eloquent\Builder|BankAccountMovement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BankAccountMovement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BankAccountMovement query()
 * @mixin \Eloquent
 * @mixin IdeHelperBankAccountMovement
 */
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
        return $this->belongsTo(Account::class, 'account_id');
    }

    final public function category(): BelongsTo
    {
        return $this->belongsTo(MovementCategory::class, 'category_id');
    }
}
