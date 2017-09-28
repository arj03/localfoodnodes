<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;

class AuthenticateAccount
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request  $request
     * @param \Closure  $next
     * @param string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!Auth::check()) {
            throw new AuthenticationException();
        } else {
            $user = Auth::user();
            if (!$user->active && !($request->is('account/user/activate') || $request->is('account/user/activate/*'))) {
                return redirect('/account/user/activate');
            } else {
                return $next($request);
            }
        }
    }
}
