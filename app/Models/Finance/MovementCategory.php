<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Finance\MovementCategory
 *
 * @method static \Illuminate\Database\Eloquent\Builder|MovementCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MovementCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MovementCategory query()
 * @mixin \Eloquent
 * @mixin IdeHelperMovementCategory
 */
class MovementCategory extends Model
{
    protected $fillable = [
        'title',
        'type',
        'slug'
    ];
}
