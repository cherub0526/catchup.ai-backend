<?php

declare(strict_types=1);

namespace App\Services\RapidApi\YoutubeMediaDownloader;

interface BaseInterface
{
    public function getUri(): string;

    public function getRequestBody(): array;
}
