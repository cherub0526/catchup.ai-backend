<?php

declare(strict_types=1);

namespace App\Models;

use Hyperf\Database\Model\SoftDeletes;

class SubscribePlanFeature extends Model
{
    use SoftDeletes;

    protected ?string $table = 'subscribe_plan_features';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [
        'subscribe_plan_id',
        'channel_limit',
        'video_limit',
        'chat_limit',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = [];
}
