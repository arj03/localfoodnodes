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

        // Log controller action for debugging
        $this->middleware(function ($request, $next) {
            error_log(var_export('Requested: ' . $request->url(), true));
            error_log(var_export('Routed to: ' . $request->route()->getAction()['uses'], true));

            return $next($request);
        });
    }
}
