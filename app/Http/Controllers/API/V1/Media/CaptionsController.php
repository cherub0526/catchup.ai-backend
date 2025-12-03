<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Media;

use Hypervel\Http\Request;
use App\Http\Resources\CaptionResource;
use App\Exceptions\InvalidRequestException;

class CaptionsController
{
    /**
     * @throws InvalidRequestException
     */
    public function index(Request $request, int $mediaId): \Hypervel\Http\Resources\Json\AnonymousResourceCollection
    {
        if (! $media = $request->user()->media()->find($mediaId)) {
            throw new InvalidRequestException(['media' => [__('validators.controllers.media.not_found')]]);
        }

        $captions = $media->captions()->orderByDesc('primary')->get(['id', 'locale']);

        return CaptionResource::collection($captions);
    }

    /**
     * @throws InvalidRequestException
     */
    public function show(Request $request, int $mediaId, int $captionId): CaptionResource
    {
        if (! $media = $request->user()->media()->find($mediaId)) {
            throw new InvalidRequestException(['media' => [__('validators.controllers.media.not_found')]]);
        }

        if (! $caption = $media->captions()->find($captionId)) {
            throw new InvalidRequestException(['caption' => [__('validators.controllers.media.caption_not_found')]]);
        }

        return new CaptionResource($caption);
    }
}
