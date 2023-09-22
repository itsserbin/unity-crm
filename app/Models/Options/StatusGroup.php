<?php

namespace App\Models\Options;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Options\StatusGroup
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Options\Status> $statuses
 * @property-read int|null $statuses_count
 * @method static \Illuminate\Database\Eloquent\Builder|StatusGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StatusGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StatusGroup query()
 * @mixin \Eloquent
 * @mixin IdeHelperStatusGroup
 */
class StatusGroup extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'hex',
        'is_system_status'
    ];

    final public function statuses(): HasMany
    {
        return $this->hasMany(Status::class, 'group_slug', 'slug');
    }
}
