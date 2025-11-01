<?php

declare(strict_types=1);

namespace App\Models;

use Hyperf\Database\Model\Relations\BelongsTo;
use Hyperf\Database\Model\SoftDeletes;

class Caption extends Model
{
    use SoftDeletes;

    public const LOCAL_ZH_TW = 'zh_tw';

    public const LOCAL_EN = 'en';

    public array $localeMap = [
        self::LOCAL_ZH_TW => '繁體中文',
        self::LOCAL_EN => '英文',
    ];

    protected ?string $table = 'captions';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = [];

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'media_id', 'id');
    }
}
