<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->hasSession()
            ? $request->session()->get('locale', config('app.locale', 'en'))
            : $request->getPreferredLanguage(['en', 'fr']);
        app()->setLocale(in_array($locale, ['en', 'fr'], true) ? $locale : 'en');

        return $next($request);
    }
}
