<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Catalog\Image
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Image newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Image newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Image query()
 * @mixin \Eloquent
 * @mixin IdeHelperImage
 */
class Image extends Model
{
    protected $fillable = [
        'alt',
        'data'
    ];

    protected $casts = [
        'data' => 'array'
    ];
}
