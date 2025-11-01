<?php

use Hypervel\Support\Facades\Route;

Route::group('/webhook', function () {
    Route::any('youtube', [\App\Http\Controllers\API\V1\Webhook\YoutubeController::class, 'store'], ['as' => 'youtube.store']);
}, ['as' => 'webhook.']);
