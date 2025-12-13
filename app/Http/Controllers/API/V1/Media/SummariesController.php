<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Media;

use Hypervel\Http\Request;
use App\Http\Resources\SummaryResource;
use App\Exceptions\InvalidRequestException;

class SummariesController
{
    /**
     * @throws InvalidRequestException
     */
    public function index(Request $request, string $mediaId)
    {
        if (!$media = $request->user()->media()->find($mediaId)) {
            throw new InvalidRequestException(['media' => [__('validators.controllers.media.not_found')]]);
        }

        $summary = $media->summaries()->first();

        return new SummaryResource($summary);
    }

    public function show(Request $request, string $mediaId, string $summaryId)
    {
    }
}
