<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Plan;
use App\Models\Price;
use App\Models\Subscription;

class SubscriptionService
{
    public function getUserSubscription(string $userId)
    {
        return Subscription::query()
            ->where('user_id', $userId)
            ->active()
            ->orderBy('start_date', 'desc')
            ->first();
    }

    public function getUserSubscriptionPlan(?Subscription $subscription)
    {
        if ($subscription) {
            $plan = $subscription->plan()->first();
        }

        // 如果沒有找訂閱的方案，預設就是免費的月訂閱方案
        if (!isset($plan) || !$plan) {
            $plan = Plan::query()->whereHas('prices', function ($builder) {
                $builder->where('unit', Price::UNIT_MONTHLY)->where('price', 0);
            })->first();
        }

        return $plan;
    }
}
