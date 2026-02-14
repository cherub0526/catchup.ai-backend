<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Models\Rss;
use App\Jobs\Rss\SyncJob;
use Hypervel\Http\Request;
use App\Services\YoutubeService;
use App\Validators\RSSValidator;
use App\Http\Resources\RSSResource;
use Psr\Http\Message\ResponseInterface;
use App\Exceptions\NotFoundHttpException;
use App\Exceptions\InvalidRequestException;
use App\Http\Controllers\AbstractController;

class RSSController extends AbstractController
{
    /**
     * @throws InvalidRequestException
     */
    public function index(Request $request)
    {
        $params = $request->only(['type', 'limit']);

        $v = new RSSValidator($params);
        $v->setIndexRules();

        if (!$v->passes()) {
            throw new InvalidRequestException($v->errors()->toArray());
        }

        $rss = $request->user()->rss()->where('type', $params['type']);
        $limit = isset($params['limit']) ? intval($params['limit']) : 0;

        return RSSResource::collection($limit > 0 && $limit <= 10 ? $rss->paginate($limit) : $rss->get());
    }

    /**
     * @throws InvalidRequestException
     */
    public function store(Request $request): RSSResource
    {
        $params = $request->only(['type', 'url']);

        $v = new RSSValidator($params);
        $v->setStoreRules();

        if (!$v->passes()) {
            throw new InvalidRequestException($v->errors()->toArray());
        }

        if ($params['type'] === Rss::TYPE_YOUTUBE) {
            $channelId = (new YoutubeService())->getChannelIdFromUrl($params['url']);
            $params['url'] = 'https://www.youtube.com/feeds/videos.xml?channel_id=' . $channelId;
        }

        $xml = @simplexml_load_file($params['url']);
        if ($xml === false) {
            throw new InvalidRequestException(['url' => [__('validators.controllers.rss.invalid_url')]]);
        }

        if (!$rss = Rss::query()->where('url', $params['url'])->first()) {
            $rss = Rss::create([
                'type'    => $params['type'],
                'title'   => (string) ($xml->title ?? 'No Title'),
                'url'     => $params['url'],
                'comment' => '',
            ]);
        }

        if (!$rss->users()->find($request->user()->id)) {
            $rss->users()->attach($request->user()->id);
        }

        SyncJob::dispatch($rss);

        return new RSSResource($rss);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function destroy(Request $request, string $rssId): ResponseInterface
    {
        if (!$rss = $request->user()->rss()->find($rssId)) {
            throw new NotFoundHttpException();
        }

        $rss->users()->detach($request->user()->id);

        return response()->make(self::RESPONSE_OK);
    }
}
