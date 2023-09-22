<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * App\Models\Catalog\Category
 *
 * @property-read \App\Models\Catalog\Image|null $preview
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Catalog\Product> $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @mixin \Eloquent
 * @mixin IdeHelperCategory
 */
class Category extends Model
{
    protected $fillable = [
        'title',
        'description',
        'preview_id',
    ];

    protected array $dates = [
        'created_at',
        'updated_at',
    ];

    final public function products(): MorphToMany
    {
        return $this->morphedbyMany(Product::class, 'categoryables');
    }

    final public function preview(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }
}
