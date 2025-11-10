<?php

declare(strict_types=1);

namespace App\Models;

use Hyperf\Database\Model\Relations\BelongsTo;

class RssSyncHistory extends Model
{
    public const STATUS_CREATED = 'created';

    public const STATUS_IN_PROGRESS = 'in_progress';

    public const STATUS_COMPLETED = 'completed';

    public static array $statusMaps = [
        self::STATUS_CREATED => 'Created',
        self::STATUS_IN_PROGRESS => 'In Progress',
        self::STATUS_COMPLETED => 'Completed',
    ];

    protected ?string $table = 'rss_sync_histories';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [
        'rss_id',
        'synced_at',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = [
        'synced_at' => 'datetime',
    ];

    public function rss(): BelongsTo
    {
        return $this->belongsTo(Rss::class, 'rss_id', 'id');
    }
}
