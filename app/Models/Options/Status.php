<?php

namespace App\Models\Options;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Options\Status
 *
 * @property-read \App\Models\Options\StatusGroup|null $group
 * @method static \Illuminate\Database\Eloquent\Builder|Status newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Status newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Status query()
 * @mixin \Eloquent
 * @mixin IdeHelperStatus
 */
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
