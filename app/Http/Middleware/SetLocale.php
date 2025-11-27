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
        // 使用底線來匹配你的語系資料夾命名慣例
        $availableLanguages = ['en', 'zh_TW', 'zh_CN'];
        $languages = explode(',', $acceptLanguage);

        foreach ($languages as $language) {
            $parts = explode(';', $language);
            // 將 'zh-TW' 轉換為 'zh_TW'
            $locale = str_replace('-', '_', trim($parts[0]));

            if (in_array($locale, $availableLanguages)) {
                return $locale;
            }
        }

        return null;
    }
}
