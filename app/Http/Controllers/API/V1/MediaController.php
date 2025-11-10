<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Exceptions\InvalidRequestException;
use App\Http\Resources\MediaResource;
use App\Validators\MediaValidator;
use Hypervel\Http\Request;

class MediaController
{
    /**
     * @throws InvalidRequestException
     */
    public function index(Request $request): \Hypervel\Http\Resources\Json\AnonymousResourceCollection
    {
        $params = $request->only(['type', 'limit']);
        $v = new MediaValidator($params);
        $v->setIndexRules();

        if (! $v->passes()) {
            throw new InvalidRequestException($v->errors()->toArray());
        }

        // TODO. add filter by date range
        $media = $request->user()->media()
            ->where('type', $params['type'])
            ->orderByDesc('published_at')
            ->paginate(
                $params['limit'] ?? 12
            );

        return MediaResource::collection($media);
    }

    /**
     * @throws InvalidRequestException
     */
    public function show(Request $request, int $mediaId): MediaResource
    {
        if (! $media = $request->user()->media()->find($mediaId)) {
            throw new InvalidRequestException(['media' => ['Media not found.']]);
        }

        return new MediaResource($media);
    }
}
