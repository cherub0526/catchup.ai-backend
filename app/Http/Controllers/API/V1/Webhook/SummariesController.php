<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Webhook;

use App\Models\Media;
use Hypervel\Http\Request;
use App\Validators\SummaryValidator;
use App\Exceptions\NotFoundHttpException;
use App\Exceptions\InvalidRequestException;
use App\Http\Controllers\AbstractController;

class SummariesController extends AbstractController
{
    /**
     * @throws NotFoundHttpException
     * @throws InvalidRequestException
     */
    public function store(Request $request, string $mediaId)
    {
        $params = $request->only(['locale', 'text']);

        $v = new SummaryValidator($params);
        $v->setStoreRules();

        if (!$v->passes()) {
            throw new InvalidRequestException($v->errors()->toArray());
        }

        if (!$media = Media::query()->find($mediaId)) {
            throw new NotFoundHttpException();
        }

        $summary = $media->summaries()->firstOrCreate(['locale' => $params['locale']]);

        $summary->fill(['text' => $params['text']])->save();

        return response()->make(self::RESPONSE_OK);
    }
}
