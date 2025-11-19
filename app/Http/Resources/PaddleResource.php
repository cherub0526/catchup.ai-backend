<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Hypervel\Http\Resources\Json\JsonResource;

class PaddleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(): array
    {
        return [
            'id' => strval($this->resource->paddle_id),
        ];
    }
}
