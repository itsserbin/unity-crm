<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Finance\ProfitAndLoss
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ProfitAndLoss newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfitAndLoss newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfitAndLoss query()
 * @mixin \Eloquent
 * @mixin IdeHelperProfitAndLoss
 */
class ProfitAndLoss extends Model
{
    protected $fillable = [
        'costs',
        'purchase_cost',
        'total_revenues',
        'net_profit',
        'business_profitability',
        'investments',
        'returned_investments',
        'dividends',
    ];
}
