<?php

declare(strict_types=1);

namespace App\Models;

class Paddle extends Model
{
    protected ?string $table = 'paddles';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [
        'foreign_type',
        'foreign_id',
        'paddle_id',
        'paddle_detail',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = [
        'paddle_detail' => 'array',
    ];

    public function parent()
    {
        return $this->belongsTo($this->foreign_type, 'foreign_type', 'id');
    }
}
