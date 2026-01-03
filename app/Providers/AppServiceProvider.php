<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Plan;
use App\Models\User;
use App\Models\Price;
use App\Observers\PlanObserver;
use App\Observers\UserObserver;
use App\Observers\PriceObserver;
use Hypervel\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        User::observe(UserObserver::class);
        Plan::observe(PlanObserver::class);
        Price::observe(PriceObserver::class);
        //        Media::observe(MediaObserver::class);
    }

    public function register(): void
    {
    }
}
