<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Product extends Model
{
    protected $fillable = [
        'availability',
        'title',
        'description',
        'trade_price',
        'price',
        'discount_price',
        'preview_id',
        'sku',
    ];

    /**
     * This code defines an array of dates that should be cast as Carbon instances when returned from the database.
     * The created_at and updated_at properties of the model will automatically be converted to instances of the Carbon class
     * when queried from the database. This allows for more convenient date manipulation and formatting in the application.
     */
    protected array $dates = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'image' => 'array'
    ];

    /**
     * The categories() function returns a morphToMany relationship between the Product model and the Category model.
     * This relationship allows the Product model to belong to multiple categories through the categoryables table.
     * The 'categoryables' table is used as a pivot table to store the relationship between the Product model and the Category model.
     *
     * @return MorphToMany relationship
     */
    final public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categoryables',);
    }

    final public function preview(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }
}
