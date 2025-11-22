<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\AbstractController;
use App\Http\Resources\PlanResource;
use App\Models\Plan;
use App\Models\Price;
use Hypervel\Http\Request;

class SubscriptionsController extends AbstractController
{
    public function index(Request $request)
    {
        // 如果沒有找訂閱的方案，預設就是免費的月訂閱方案
        if ($subscription = $request->user()->subscriptions()->active()->first()) {
            $plan = $subscription->plan()->first();
        }

        if (! isset($plan)) {
            $plan = Plan::query()->whereHas('prices', function ($builder) {
                $builder->where('unit', Price::UNIT_MONTHLY)->where('price', 0);
            })->first();
        }

        $plan->load([
            'prices' => function ($builder) use ($subscription) {
                $subscription
                    ? $builder->where('id', $subscription->price_id)
                    : $builder->where('unit', Price::UNIT_MONTHLY)->where('price', 0);
            },
        ]);

        return new PlanResource($plan);
    }

    public function store(Request $request)
    {
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
