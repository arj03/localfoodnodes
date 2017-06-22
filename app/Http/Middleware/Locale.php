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
        if (Auth::check() && Auth::user()->active) {
            $user = Auth::user();
            \App::setLocale($user->language);
        } else if (\Session::get('locale')) {
            \App::setLocale(\Session::get('locale'));
        } else if ($request->has('lang') && array_key_exists($request->get('lang'), config('app.locales'))) {
            \Session::put('locale', $request->get('lang'));
            \App::setLocale($request->get('lang'));
        }

        return $next($request);
    }
}
