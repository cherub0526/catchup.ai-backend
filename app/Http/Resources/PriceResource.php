<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Hypervel\Http\Resources\Json\JsonResource;

class PriceResource extends JsonResource
{
    public ?string $wrap = null;

    /**
     * Transform the resource into an array.
     */
    public function toArray(): array
    {
        return [
            'id' => strval($this->resource->id),
            'paddle_price_id' => strval($this->resource->paddle_price_id),
            'unit' => strval($this->resource->unit),
            'price' => floatval($this->resource->price),
        ];
    }
}
