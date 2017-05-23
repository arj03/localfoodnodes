<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware(function ($request, $next) {
            view()->composer('*', function($view) {
                $view_name =  'page ' . str_replace('.', '-', $view->getName());
                view()->share('viewName', $view_name);
            });
            return $next($request);
        });
    }

    public function findOutMore()
    {
        return view('public.pages.find-out-more');
    }

    public function membership()
    {
        return view('public.pages.membership');
    }
}
