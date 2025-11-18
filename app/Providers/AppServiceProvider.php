<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Plan;
use App\Observers\PlanObserver;
use Hypervel\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Plan::observe(PlanObserver::class);
    }

    public function register(): void
    {
    }
}
