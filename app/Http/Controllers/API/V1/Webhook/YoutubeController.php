<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Webhook;

use Hypervel\Http\Request;
use Hyperf\Resource\Json\JsonResource;

class YoutubeController
{
    public function store(Request $request)
    {
        $params = $request->only(['video_detail', 'audio_detail']);

        return response()->json($params);
    }
}
