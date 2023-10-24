<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperSubscription
 */
class Subscription extends Model
{
    final public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    final public function plan(): BelongsTo
    {
        return $this->belongsTo(TariffPlan::class, 'plan_id');
    }
}
