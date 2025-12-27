<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Webhook;

use App\Models\Media;
use App\Models\Caption;
use Hypervel\Http\Request;
use App\Utils\Const\ISO6391;
use App\Validators\GroqValidator;
use App\Exceptions\NotFoundHttpException;
use App\Exceptions\InvalidRequestException;
use App\Http\Controllers\AbstractController;

class GroqController extends AbstractController
{
    /**
     * @throws NotFoundHttpException
     * @throws InvalidRequestException
     */
    public function store(Request $request, string $mediaId)
    {
        $params = $request->only(['status', 'data']);

        $v = new GroqValidator($params);
        $v->setStoreRules();

        if (!$v->passes()) {
            throw new InvalidRequestException($v->errors()->toArray());
        }

        if (!$media = Media::query()->find($mediaId)) {
            throw new NotFoundHttpException();
        }

        $media->fill([
            'status' => match ($params['status']) {
                'success' => Media::STATUS_TRANSCRIBED,
                'error'   => Media::STATUS_TRANSCRIBE_FAILED,
                default   => Media::STATUS_TRANSCRIBING,
            },
        ])->save();

        if ($params['status'] === GroqValidator::STATUS_SUCCESS) {
            $locale = ISO6391::getCodeByName($params['data']['language']);

            $caption = Caption::query()->firstOrCreate([
                'locale'   => $locale,
                'media_id' => $mediaId,
                'primary'  => true,
            ]);

            $caption->fill([
                'text'          => $params['data']['text'],
                'segments'      => $params['data']['segments'],
                'word_segments' => $params['data']['words'],
            ])->save();
        }

        return response()->make(self::RESPONSE_OK);
    }
}
