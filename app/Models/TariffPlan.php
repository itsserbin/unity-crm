<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperTariffPlan
 */
class TariffPlan extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'duration_days',
        'features'
    ];

    protected $casts = [
        'features' => 'array'
    ];

    final public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}
