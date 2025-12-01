<?php

declare(strict_types=1);

use Hypervel\Support\Facades\Route;

Route::group('/auth', function () {
    Route::post(
        '/forgot-password',
        [
            'as' => 'forgot-password',
            'uses' => \App\Http\Controllers\API\V1\Auth\ForgotPasswordController::class . '@store',
        ]
    );

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

    Route::put('/', [
        'as' => 'update',
        'uses' => \App\Http\Controllers\API\V1\UsersController::class . '@update',
        'middleware' => ['auth'],
    ]);
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
    Route::delete('/{rssId:[0-9]+}', [
        'as' => 'destroy',
        'uses' => \App\Http\Controllers\API\V1\RSSController::class . '@destroy',
        'middleware' => ['auth'],
    ]);
}, ['as' => 'rss']);

Route::group('/media', function () {
    Route::get(
        '/',
        [
            'as' => 'index',
            'uses' => \App\Http\Controllers\API\V1\MediaController::class . '@index',
            'middleware' => ['auth'],
        ]
    );
    Route::get(
        '/{mediaId:[0-9]+}',
        [
            'as' => 'show',
            'uses' => \App\Http\Controllers\API\V1\MediaController::class . '@show',
            'middleware' => ['auth'],
        ]
    );

    Route::group('/{mediaId:[0-9]+}/summaries', function () {
        Route::get(
            '/',
            [
                'as' => 'index',
                'uses' => \App\Http\Controllers\API\V1\Media\SummariesController::class . '@index',
                'middleware' => ['auth'],
            ]
        );
        Route::get(
            '/{summaryId:[0-9]+}',
            [
                'as' => 'show',
                'uses' => \App\Http\Controllers\API\V1\Media\SummariesController::class . '@show',
                'middleware' => ['auth'],
            ]
        );
    }, ['as' => 'summaries']);

    Route::group('/{mediaId:[0-9]+}/captions', function () {
        Route::get(
            '/',
            [
                'as' => 'index',
                'uses' => \App\Http\Controllers\API\V1\Media\CaptionsController::class . '@index',
                'middleware' => ['auth'],
            ]
        );
        Route::get(
            '/{captionId}',
            [
                'as' => 'show',
                'uses' => \App\Http\Controllers\API\V1\Media\CaptionsController::class . '@show',
                'middleware' => ['auth'],
            ]
        );
    }, ['as' => 'captions']);

    Route::group('/{mediaId:[0-9]+}/chat', function () {
        Route::post('/', [
            'as' => 'store',
            'uses' => \App\Http\Controllers\API\V1\Media\ChatController::class . '@store',
            'middleware' => ['auth'],
        ]);
    }, ['as' => 'chat']);
}, ['as' => 'media']);

Route::group('/subscriptions', function () {
    Route::get(
        '/',
        [
            'as' => 'index',
            'uses' => \App\Http\Controllers\API\V1\SubscriptionsController::class . '@index',
            'middleware' => ['auth'],
        ]
    );
    Route::post('/', [
        'as' => 'store',
        'uses' => \App\Http\Controllers\API\V1\SubscriptionsController::class . '@store',
        'middleware' => ['auth'],
    ]);
    Route::put('/{subscriptionId}', [
        'as' => 'update',
        'uses' => \App\Http\Controllers\API\V1\SubscriptionsController::class . '@update',
        'middleware' => ['auth'],
    ]);
    Route::delete('/{subscriptionId}', [
        'as' => 'destroy',
        'uses' => \App\Http\Controllers\API\V1\SubscriptionsController::class . '@destroy',
        'middleware' => ['auth'],
    ]);

    Route::get('/usage', [
        'as' => 'usage',
        'uses' => \App\Http\Controllers\API\V1\SubscriptionsController::class . '@usage',
        'middleware' => ['auth'],
    ]);
}, ['as' => 'subscriptions']);

Route::group('/plans', function () {
    Route::get(
        '/',
        [
            'as' => 'index',
            'uses' => \App\Http\Controllers\API\V1\Subscriptions\PlansController::class . '@index',
        ]
    );
}, ['as' => 'plans']);

Route::group('/webhook', function () {
    Route::any(
        'youtube',
        ['as' => 'youtube.store', 'uses' => \App\Http\Controllers\API\V1\WebhookController::class . '@youtube']
    );

    Route::post(
        '/paddle',
        [
            'as' => 'paddle',
            'uses' => \App\Http\Controllers\API\V1\Webhook\PaddleController::class . '@store',
        ]
    );
}, ['as' => 'webhook']);
