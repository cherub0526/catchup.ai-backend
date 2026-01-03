<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Webhook;

use App\Models\Media;
use Hypervel\Http\Request;
use App\Exceptions\NotFoundHttpException;
use App\Exceptions\InvalidRequestException;
use App\Http\Controllers\AbstractController;
use App\Validators\YoutubeMp3DownloaderValidator;

class YoutubeMp3DownloaderController extends AbstractController
{
    /**
     * @throws NotFoundHttpException
     * @throws InvalidRequestException
     */
    public function store(Request $request, string $mediaId)
    {
        $params = $request->only(['status', 'data']);

        $v = new YoutubeMp3DownloaderValidator($params);
        $v->setStoreRules();

        if (!$v->passes()) {
            throw new InvalidRequestException($v->errors()->toArray());
        }

        if (!$media = Media::query()->find($mediaId)) {
            throw new NotFoundHttpException();
        }

        $media->fill([
            'audio_detail' => $params['data'],
            'status'       => Media::STATUS_PROGRESS,
        ])->save();

        return response()->make(self::RESPONSE_OK);
    }
}
