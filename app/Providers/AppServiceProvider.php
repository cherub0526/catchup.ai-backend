<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Plan;
use App\Models\Price;
use App\Models\User;
use App\Observers\PlanObserver;
use App\Observers\PriceObserver;
use App\Observers\UserObserver;
use Hypervel\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        User::observe(UserObserver::class);
        Plan::observe(PlanObserver::class);
        Price::observe(PriceObserver::class);
    }

    public function register(): void
    {
    }
}
