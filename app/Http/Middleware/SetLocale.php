<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Hypervel\Support\Facades\App;
use Psr\Http\Message\ServerRequestInterface;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(ServerRequestInterface $request, Closure $next)
    {
        if ($request->hasHeader('Accept-Language')) {
            $acceptLanguage = $request->getHeaderLine('Accept-Language');
            $preferredLanguage = $this->getPreferredLanguage($acceptLanguage);

            if ($preferredLanguage) {
                App::setLocale($preferredLanguage);
            }
        }

        return $next($request);
    }

    private function getPreferredLanguage(string $acceptLanguage): ?string
    {
        $availableLanguages = ['en', 'zh_TW', 'zh_CN'];
        $languages = explode(',', $acceptLanguage);

        foreach ($languages as $language) {
            $parts = explode(';', $language);
            $locale = trim($parts[0]);

            if (in_array($locale, $availableLanguages)) {
                return $locale;
            }
        }

        return null;
    }
}
