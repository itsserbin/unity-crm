<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Finance\BankAccount
 *
 * @method static \Illuminate\Database\Eloquent\Builder|BankAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BankAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BankAccount query()
 * @mixin \Eloquent
 * @mixin IdeHelperBankAccount
 */
class BankAccount extends Model
{
    protected $fillable = [
        'data',
        'name',
        'balance',
    ];

    protected $casts = [
        'data' => 'array'
    ];

    protected array $dates = [
        'date'
    ];
}
