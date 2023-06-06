<?php

namespace App\Models\Options;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
