<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next) {
        if(app()->environment('local')) {
            logger()->debug('Language Middleware', [
                'all_cookies' => $request->cookies->all(),
                'lang_cookie' => $request->cookie('lang')
            ]);
        }


        
        return $next($request);
    }
}
