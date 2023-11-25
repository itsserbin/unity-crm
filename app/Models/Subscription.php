<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperSubscription
 */
class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'start_date',
        'end_date',
    ];

    final public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    final public function plan(): BelongsTo
    {
        return $this->belongsTo(TariffPlan::class, 'plan_id');
    }
}
