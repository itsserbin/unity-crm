<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Finance\Account
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Account newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Account newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Account query()
 * @mixin \Eloquent
 * @mixin IdeHelperAccount
 */
class Account extends Model
{
    protected $fillable = [
        'title',
        'balance'
    ];
}
