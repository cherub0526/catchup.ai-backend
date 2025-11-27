<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Hypervel\Http\Request;
use Hypervel\Support\Facades\App;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->hasHeader('Accept-Language')) {
            App::setLocale($request->prefers(['en', 'zh-TW']));
        }

        return $next($request);
    }
}
