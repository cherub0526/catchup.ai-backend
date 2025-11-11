<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Hypervel\Http\Resources\Json\JsonResource;

class CaptionResource extends JsonResource
{
    public ?string $wrap = null;

    /**
     * Transform the resource into an array.
     */
    public function toArray(): array
    {
        return [
            'id' => intval($this->resource->id),
            'locale' => strval($this->resource->locale),
            'segments' => array_map(function ($segment) {
                return [
                    'start' => floatval($segment['start']),
                    'end' => floatval($segment['end']),
                    'text' => trim(strval($segment['text'])),
                ];
            }, $this->resource->segments ?? []),
        ];
    }
}
