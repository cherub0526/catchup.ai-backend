<?php

declare(strict_types=1);

namespace App\Services;

use Hypervel\Support\Facades\Http;

class RssFeedAsapService
{
    public function execute($url): array
    {
        $response = Http::get('https://api.rssfeedasap.com', [
            'url' => $url,
        ]);

        return $response->json();
    }
}
