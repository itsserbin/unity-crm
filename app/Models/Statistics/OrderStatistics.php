<?php

namespace App\Models\Statistics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Statistics\Order
 *
 * @method static \Illuminate\Database\Eloquent\Builder|OrderStatistics newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderStatistics newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderStatistics query()
 * @mixin \Eloquent
 * @mixin IdeHelperOrderStatistics
 */
class OrderStatistics extends Model
{
    use HasFactory;
}
