<?php

declare(strict_types=1);

namespace App\Models;

use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\SoftDeletes;
use Hypervel\Database\Eloquent\Factories\HasFactory;
use Hypervel\Database\Eloquent\Relations\HasMany;
use Hypervel\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;
    use SoftDeletes;

    public const SOCIAL_TYPE_LOCAL = 'local';

    public const SOCIAL_TYPE_FACEBOOK = 'facebook';

    public const SOCIAL_TYPE_GOOGLE = 'google';

    protected array $with = ['paddle'];

    protected ?string $table = 'users';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [
        'account',
        'name',
        'email',
        'email_verified_at',
        'password',
        'social_type',
        'paddle_customer_id',
    ];

    public function oauths(): HasMany
    {
        return $this->hasMany(Oauth::class, 'user_id', 'id');
    }

    public function rss()
    {
        return $this->belongsToMany(
            Rss::class,
            'userables',
            'user_id',
            'rss_id'
        )->wherePivot('media_id', null)->withTimestamps();
    }

    public function media()
    {
        return $this->belongsToMany(
            Media::class,
            'userables',
            'user_id',
            'media_id'
        )->withTimestamps();
    }

    public function paddle(): Builder|\Hypervel\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Paddle::class, 'foreign_id', 'id')->where('foreign_type', self::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'user_id', 'id');
    }
}
