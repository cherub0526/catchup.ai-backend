<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\AbstractController;
use App\Services\PaddleClient;
use Hypervel\Http\Request;
use Paddle\SDK\Notifications\Secret;
use Paddle\SDK\Notifications\Verifier;

class SubscriptionsController extends AbstractController
{
    public function index(Request $request)
    {
    }

    public function store(Request $request)
    {
        $params = $request->only(['transaction_id']);
        
    }

    public function destroy(Request $request, int $subscriptionId)
    {
    }

    public function usage(Request $request): \Psr\Http\Message\ResponseInterface
    {
        $between = [
            'start' => now()->startOfMonth(),
            'end' => now()->endOfMonth(),
        ];
        return response()->json([
            'data' => [
                'plan' => [
                    'channels' => 1,
                    'media' => 5,
                ],
                'usage' => [
                    'channels' => $request->user()->rss()->count(),
                    'media' => $request->user()->media()->whereBetween('userables.created_at', $between)->count(),
                ],
            ],
        ]);
    }
}
