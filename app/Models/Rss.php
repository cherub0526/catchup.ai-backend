<?php

declare(strict_types=1);

namespace App\Models;

use Hyperf\Database\Model\SoftDeletes;

class Rss extends Model
{
    use SoftDeletes;

    public const TYPE_YOUTUBE = 'youtube';

    public array $typeMaps = [
        self::TYPE_YOUTUBE => 'Youtube',
    ];

    protected ?string $table = 'rss';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [
        'type',
        'title',
        'url',
        'comment',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = [];
}
