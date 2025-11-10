<?php

declare(strict_types=1);

use Hypervel\Support\Facades\Route;

Route::get('/test', function () {
    $rss = \App\Models\Rss::first();
    $job = (new \App\Jobs\Rss\SyncJob($rss));
    $job->handle();
});
