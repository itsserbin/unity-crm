<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Category extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
    ];

    protected array $dates = [
        'created_at',
        'updated_at',
    ];

    final public function products(): MorphToMany
    {
        return $this->morphedbyMany(Product::class, 'categoryables');
    }
}
