<?php

declare(strict_types=1);

use Hypervel\Support\Facades\Route;

Route::group('/auth', function () {
    Route::post(
        '/register',
        ['as' => 'register', 'uses' => \App\Http\Controllers\API\V1\AuthController::class . '@register']
    );

    Route::post('/', ['as' => 'store', 'uses' => \App\Http\Controllers\API\V1\AuthController::class . '@store']);

    Route::post(
        '/refresh',
        ['as' => 'refresh', 'uses' => \App\Http\Controllers\API\V1\AuthController::class . '@refresh']
    );
    Route::post(
        '/logout',
        [
            'as' => 'logout',
            'uses' => \App\Http\Controllers\API\V1\AuthController::class . '@logout',
            'middleware' => ['auth'],
        ]
    );
}, ['as' => 'auth']);

Route::group('/users', function () {
    Route::get(
        '/',
        [
            'as' => 'index',
            'uses' => \App\Http\Controllers\API\V1\UsersController::class . '@index',
            'middleware' => ['auth'],
        ]
    );
}, ['as' => 'users']);

Route::group('/rss', function () {
    Route::get(
        '/',
        [
            'as' => 'index',
            'uses' => \App\Http\Controllers\API\V1\RSSController::class . '@index',
            'middleware' => ['auth'],
        ]
    );
    Route::post(
        '/',
        [
            'as' => 'store',
            'uses' => \App\Http\Controllers\API\V1\RSSController::class . '@store',
            'middleware' => ['auth'],
        ]
    );
    Route::delete('/{rssId}', [
        'as' => 'destroy',
        'uses' => \App\Http\Controllers\API\V1\RSSController::class . '@destroy',
        'middleware' => ['auth'],
    ]);
}, ['as' => 'rss']);

Route::group('/webhook', function () {
    Route::any(
        'youtube',
        ['as' => 'youtube.store', 'uses' => \App\Http\Controllers\API\V1\WebhookController::class . '@youtube']
    );
}, ['as' => 'webhook']);
