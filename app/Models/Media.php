<?php

declare(strict_types=1);

namespace App\Models;

use Hyperf\Database\Model\SoftDeletes;
use Hyperf\Database\Model\Relations\HasMany;
use Hyperf\Database\Model\Relations\BelongsToMany;

class Media extends Model
{
    use SoftDeletes;

    public const string STATUS_CREATED = 'created';

    public const string STATUS_PROGRESS = 'progress';

    public const string STATUS_TRANSCRIBING = 'transcribing';

    public const string STATUS_TRANSCRIBED = 'transcribed';
    public const STATUS_TRANSCRIBE_FAILED = 'transcribe_failed';

    public const STATUS_SUMMARIZING = 'summarizing';

    public const STATUS_SUMMARIZED = 'summarized';

    public const STATUS_SUMMARIZE_FAILED = 'summarize_failed';

    public const STATUS_READY = 'ready';

    public const STATUS_CANCELLED = 'cancelled';

    public const STATUS_FAILED = 'failed';

    public const TYPE_YOUTUBE = 'youtube';

    public const TYPE_SPOTIFY = 'spotify';

    public static array $statusMap = [
        self::STATUS_CREATED           => '已建立',
        self::STATUS_PROGRESS          => '處理中',
        self::STATUS_TRANSCRIBING      => '轉錄中',
        self::STATUS_TRANSCRIBED       => '轉錄完成',
        self::STATUS_TRANSCRIBE_FAILED => '轉錄失敗',
        self::STATUS_SUMMARIZING       => '摘要中',
        self::STATUS_SUMMARIZED        => '摘要完成',
        self::STATUS_SUMMARIZE_FAILED  => '摘要失敗',
        self::STATUS_READY             => '完成',
        self::STATUS_CANCELLED         => '取消',
        self::STATUS_FAILED            => '失敗',
    ];

    public static array $typeMaps = [
        self::TYPE_YOUTUBE => 'YouTube',
        self::TYPE_SPOTIFY => 'Spotify',
    ];

    protected ?string $table = 'media';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [
        'type',
        'resource_id',
        'title',
        'description',
        'duration',
        'thumbnail',
        'published_at',
        'status',
        'video_detail',
        'audio_detail',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = [
        'duration'     => 'integer',
        'video_detail' => 'array',
        'audio_detail' => 'array',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'userables', 'media_id', 'user_id')->withTimestamps();
    }

    public function captions(): HasMany
    {
        return $this->hasMany(Caption::class, 'media_id', 'id');
    }

    public function summaries(): HasMany
    {
        return $this->hasMany(Summary::class, 'media_id', 'id');
    }
}
