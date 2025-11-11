<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Media;

use App\Exceptions\InvalidRequestException;
use App\Http\Resources\CaptionResource;
use Hypervel\Http\Request;

class CaptionsController
{
    /**
     * @throws InvalidRequestException
     */
    public function index(Request $request, int $mediaId): \Hypervel\Http\Resources\Json\AnonymousResourceCollection
    {
        if (! $media = $request->user()->media()->find($mediaId)) {
            throw new InvalidRequestException(['media' => ['Media not found.']]);
        }

        $captions = $media->captions()->get(['id', 'locale']);

        return CaptionResource::collection($captions);
    }

    /**
     * @throws InvalidRequestException
     */
    public function show(Request $request, int $mediaId, int $captionId): CaptionResource
    {
        if (! $media = $request->user()->media()->find($mediaId)) {
            throw new InvalidRequestException(['media' => ['Media not found.']]);
        }

        if (! $caption = $media->captions()->find($captionId)) {
            throw new InvalidRequestException(['caption' => ['Caption not found.']]);
        }

        return new CaptionResource($caption);
    }
}
