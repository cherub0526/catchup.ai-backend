<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Hypervel\Http\Resources\Json\JsonResource;

class SummaryResource extends JsonResource
{
    public ?string $wrap = null;

    /**
     * Transform the resource into an array.
     */
    public function toArray(): array
    {
        return parent::toArray();
    }
}
