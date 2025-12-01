<?php

declare(strict_types=1);

namespace App\Services\RapidApi;

use Hypervel\Support\Facades\Http;
use App\Services\RapidApi\YoutubeMediaDownloader\Video;

class YoutubeMediaDownloader
{
    protected string $rapidApiKey;

    protected string $rapidApiHost = 'youtube-media-downloader.p.rapidapi.com';

    public function __construct()
    {
        $this->rapidApiKey = config('services.rapidapi.key');
    }

    public function video(): Video
    {
        return new Video($this);
    }

    public function send(string $method, string $uri, array $query = [])
    {
        $response = Http::withHeaders([
            'X-Rapidapi-Key' => $this->rapidApiKey,
            'X-Rapidapi-Host' => $this->rapidApiHost,
        ])->{$method}(
            'https://' . $this->rapidApiHost . $uri,
            $query
        );

        return $response->json();
    }
}
