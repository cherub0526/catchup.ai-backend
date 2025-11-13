<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Media;

use App\Exceptions\InvalidRequestException;
use App\Http\Resources\SummaryResource;
use Hypervel\Http\Request;

class SummariesController
{
    /**
     * @throws InvalidRequestException
     */
    public function index(Request $request, int $mediaId)
    {
        if (! $media = $request->user()->media()->find($mediaId)) {
            throw new InvalidRequestException(['media' => ['Media not found.']]);
        }

        $summary = $media->summaries()->first();

        return new SummaryResource($summary);
    }

    public function show(Request $request, int $mediaId, int $summaryId)
    {
    }
}
