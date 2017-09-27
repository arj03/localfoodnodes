<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapAccountRoutes();
        $this->mapApiRoutes();
        $this->mapAdminRoutes();
        $this->mapAuthRoutes();

        // Needs to be last since the permalink controller tries to match all request
        // and returns a 404 is fail.
        $this->mapPublicRoutes();
    }

    /**
     * Define the account routes for the application.
     *
     * @return void
     */
    protected function mapAccountRoutes()
    {
        $options = [
            'middleware' => ['web', 'auth.account'],
            'namespace' => $this->namespace,
            'prefix' => 'account',
        ];

        Route::group($options, function ($router) {
            require base_path('routes/account.php');
        });
    }

    /**
     * Define the api routes for the application.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        $options = [
            'middleware' => 'client_credentials',
            'namespace' => $this->namespace,
            'prefix' => 'api',
        ];

        Route::group($options, function ($router) {
            require base_path('routes/api.php');
        });
    }

    /**
     * Define the admin routes for the application.
     *
     * @return void
     */
    protected function mapAdminRoutes()
    {
        $options = [
            'middleware' => ['web', 'auth.admin'],
            'namespace' => $this->namespace,
            'prefix' => 'admin',
        ];

        Route::group($options, function ($router) {
            require base_path('routes/admin.php');
        });
    }

    /**
     * Define the auth and password routes for the application.
     *
     * @return void
     */
    protected function mapAuthRoutes()
    {
        $options = [
            'middleware' => 'web',
            'namespace' => $this->namespace,
        ];

        Route::group($options, function ($router) {
            require base_path('routes/auth.php');
        });
    }

    /**
     * Define the public routes for the application.
     *
     * @return void
     */
    protected function mapPublicRoutes()
    {
        $options = [
            'middleware' => 'web',
            'namespace' => $this->namespace,
        ];

        Route::group($options, function ($router) {
            require base_path('routes/public.php');
        });
    }
}
