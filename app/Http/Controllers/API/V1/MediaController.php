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
        $params = $request->only(['type', 'range', 'limit']);
        $v = new MediaValidator($params);
        $v->setIndexRules();

        if (! $v->passes()) {
            throw new InvalidRequestException($v->errors()->toArray());
        }

        $media = $request->user()->media()
            ->where('type', $params['type'])
            ->when($params['range'] ?? false, function ($query) use ($params) {
                $date = match ($params['range']) {
                    'today' => now()->startOfDay(),
                    'week' => now()->subWeek()->startOfDay(),
                    'month' => now()->subMonth()->startOfDay(),
                    'year' => now()->subYear()->startOfDay(),
                    default => null,
                };
                if ($date) {
                    $query->where('published_at', '>=', $date);
                }
            })
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
