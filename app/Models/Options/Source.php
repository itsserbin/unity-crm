<?php

namespace App\Models\Options;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Options\Source
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Source newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Source newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Source query()
 * @mixin \Eloquent
 * @mixin IdeHelperSource
 */
class Source extends Model
{
    protected $fillable = [
        'title',
        'source'
    ];
}
