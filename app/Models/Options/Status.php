<?php

namespace App\Models\Options;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Status extends Model
{
    protected $fillable = [
        'group_slug',
        'title',
        'orders_count',
        'is_system_status',
        'published'
    ];

    final public function group(): BelongsTo
    {
        return $this->belongsTo(StatusGroup::class, 'group_slug', 'slug');
    }
}
