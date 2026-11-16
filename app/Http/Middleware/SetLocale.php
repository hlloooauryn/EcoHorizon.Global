<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Supported application locales.
     */
    public const SUPPORTED_LOCALES = ['en', 'id'];

    /**
     * Handle an incoming request and set application locale from session, cookie, or query parameter.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Direct query parameter ?lang=en or ?lang=id on any URL
        if ($request->has('lang')) {
            $queryLang = strtolower((string) $request->query('lang'));
            if (in_array($queryLang, self::SUPPORTED_LOCALES, true)) {
                session(['locale' => $queryLang]);
                cookie()->queue(cookie('locale', $queryLang, 60 * 24 * 365, null, null, false, false));
                App::setLocale($queryLang);
            }
        }

        // 2. Read from session or persistent cookie or fallback to default
        $locale = session('locale') 
            ?? $request->cookie('locale') 
            ?? config('app.locale', 'en');

        if (in_array($locale, self::SUPPORTED_LOCALES, true)) {
            App::setLocale($locale);
            if (session('locale') !== $locale) {
                session(['locale' => $locale]);
            }
        } else {
            App::setLocale('en');
        }

        return $next($request);
    }
}
