<?php

declare(strict_types=1);

namespace App\Models;

use Hypervel\Database\Eloquent\Concerns\HasUuids;
use Hypervel\Database\Eloquent\SoftDeletes;

class Price extends Model
{
    use HasUuids;
    use SoftDeletes;

    public const UNIT_MONTHLY = 'monthly';

    public const UNIT_QUARTERLY = 'quarterly';

    public const UNIT_ANNUALLY = 'annually';

    public static array $unitMaps = [
        self::UNIT_MONTHLY => '每月',
        self::UNIT_QUARTERLY => '每季',
        self::UNIT_ANNUALLY => '每年',
    ];

    protected ?string $table = 'prices';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [
        'subscribe_plan_id',
        'paddle_price_id',
        'unit',
        'price',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = [];

    public function plan(): \Hypervel\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Plan::class, 'subscribe_plan_id', 'id');
    }
}
