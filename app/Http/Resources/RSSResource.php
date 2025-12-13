<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Hypervel\Http\Resources\Json\JsonResource;

class RSSResource extends JsonResource
{
    public ?string $wrap = null;

    /**
     * Transform the resource into an array.
     */
    public function toArray(): array
    {
        return [
            'id'         => strval($this->resource->id),
            'type'       => strval($this->resource->type),
            'url'        => strval($this->resource->url),
            'title'      => strval($this->resource->title),
            'created_at' => strval($this->resource->created_at),
        ];
    }
}
