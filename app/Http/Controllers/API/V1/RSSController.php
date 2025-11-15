<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Exceptions\InvalidRequestException;
use App\Http\Controllers\AbstractController;
use App\Http\Resources\RSSResource;
use App\Jobs\Rss\SyncJob;
use App\Models\Rss;
use App\Validators\RSSValidator;
use Hypervel\Http\Request;

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

        if (! $v->passes()) {
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

        if (! $v->passes()) {
            throw new InvalidRequestException($v->errors()->toArray());
        }

        if ($params['type'] === Rss::TYPE_YOUTUBE) {
            $params['url'] = 'https://www.youtube.com/feeds/videos.xml?channel_id=' . $params['url'];
        }

        $xml = @simplexml_load_file($params['url']);
        if ($xml === false) {
            throw new InvalidRequestException(['url' => ['Invalid RSS URL.']]);
        }

        $rss = \App\Models\Rss::create([
            'type' => $params['type'],
            'title' => (string) ($xml->title ?? 'No Title'),
            'url' => $params['url'],
            'comment' => '',
        ]);

        $rss->users()->attach($request->user()->id);

        SyncJob::dispatch($rss);

        return new RSSResource($rss);
    }

    /**
     * @throws InvalidRequestException
     */
    public function destroy(Request $request, int $rssId): \Psr\Http\Message\ResponseInterface
    {
        if (! $rss = $request->user()->rss()->find($rssId)) {
            throw new InvalidRequestException(['rss_id' => ['RSS not found.']]);
        }

        $rss->users()->detach($request->user()->id);

        return response()->make('OK.');
    }
}
