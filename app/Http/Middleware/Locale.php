<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // Use users chosen language
        if (Auth::check() && Auth::user()->active) {
            $user = Auth::user();
            \App::setLocale($user->language);
        }

        // Use session if set
        else if (\Session::get('locale')) {
            \App::setLocale(\Session::get('locale'));
        }

        // If lang value is present in request store it
        else if ($request->has('lang') && array_key_exists($request->get('lang'), config('app.locales'))) {
            \Session::put('locale', $request->get('lang'));
            \App::setLocale($request->get('lang'));
        }

        // Use browser language setting
        else {
            $browserLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            \App::setLocale($browserLang);
        }

        return $next($request);
    }
}
