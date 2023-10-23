<?php

namespace App\Models\Statistics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Statistics\Profit
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ProfitStatistics newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfitStatistics newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfitStatistics query()
 * @mixin \Eloquent
 * @mixin IdeHelperProfitStatistics
 */
class ProfitStatistics extends Model
{
    use HasFactory;
}
