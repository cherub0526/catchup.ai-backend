<?php

declare(strict_types=1);

namespace App\Models;

use Hyperf\Database\Model\SoftDeletes;
use Hypervel\Database\Eloquent\Concerns\HasUuids;

class Plan extends Model
{
    use HasUuids;
    use SoftDeletes;

    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    public static array $statusMaps = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'Inactive',
    ];

    protected ?string $table = 'plans';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [
        'paddle_plan_id',
        'title',
        'description',
        'channel_limit',
        'video_limit',
        'chat_limit',
        'sort',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = [
        'sort' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function prices(): \Hypervel\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Price::class, 'plan_id', 'id');
    }
}
