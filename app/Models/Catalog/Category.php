<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

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
