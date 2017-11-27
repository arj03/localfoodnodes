<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use View;
use Illuminate\Support\Facades\Auth;
use App\User\User;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Constructor.
     */
    public function __construct()
    {
        // Add user object to all views
        $this->middleware(function ($request, $next) {
            $user = Auth::user() ?: new User();
            View::share('user', $user);

            return $next($request);
        });

        // Add body class based on view
        $this->middleware(function ($request, $next) {
            view()->composer('*', function($view) {
                $view_name = str_replace('.', '-', $view->getName());
                view()->share('viewName', $view_name);
            });

            return $next($request);
        });

        // Set language
        $this->middleware(function ($request, $next) {
            $this->setLang($request, $next);

            return $next($request);
        });
    }

    /**
     * Set language middleware.
     *
     * @param Request $request
     * @param Closure $next
     */
    private function setLang($request, $next)
    {
        \App::setLocale($this->getLang());
    }

    /**
     * Get current language.
     *
     * @return string
     */
    public function getLang()
    {
        $request = \Request();

        // Use users chosen language
        if (Auth::check() && Auth::user()->active) {
            $user = Auth::user();
            $lang = $user->language;
        }

        // Use session if set
        else if (\Session::get('locale')) {
            $lang = \Session::get('locale');
        }

        // If lang value is present in request store it
        else if ($request->has('lang') && array_key_exists($request->get('lang'), config('app.locales'))) {
            \Session::put('locale', $request->get('lang'));
            $lang = $request->get('lang');
        }

        // Use browser language setting
        else {
            $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        }

        return $lang;
    }
}
