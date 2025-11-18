<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Subscriptions;

use App\Http\Resources\PlanResource;
use App\Models\Plan;

class PlansController
{
    public function index(): \Hypervel\Http\Resources\Json\AnonymousResourceCollection
    {
        $plans = Plan::query()->active()
            ->with(['prices'])
            ->orderBy('sort', 'asc')
            ->get();

        $plans = $plans->filter(function (Plan $plan) {
            return $plan->prices->isNotEmpty();
        });

        return PlanResource::collection($plans);
    }
}
