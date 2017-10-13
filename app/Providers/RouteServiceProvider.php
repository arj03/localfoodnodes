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
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->accountRoutes();
        $this->apiRoutes();
        $this->adminRoutes();
        $this->authRoutes();

        // Custom producer domains
        $this->producerDomainRoutes();

        // Needs to be last since the permalink controller tries to match all request
        // and returns a 404 is fail.
        $this->publicRoutes();
    }

    /**
     * Define the account routes for the application.
     *
     * @return void
     */
    protected function accountRoutes()
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
    protected function apiRoutes()
    {
        $options = [
            'middleware' => 'client',
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
    protected function adminRoutes()
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
    protected function authRoutes()
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
     * Producer custom domains routes.
     *
     * Todo: If no domain match redirect to .env domain
     *
     * @return void
     */
    protected function producerDomainRoutes()
    {
        $producers = [
            // ['id' => '2', 'domain' => 'test.dev'],
            // ['id' => '3', 'domain' => 'dev.test']
        ];

        foreach ($producers as $producer) {
            Route::domain($producer['domain'])->group(function() use ($producer) {
                Route::get('/', function(\Illuminate\Http\Request $request) use ($producer) {
                    return app('App\Http\Controllers\IndexController')->producer($request, $producer['id']);
                });
            });
        }
    }

    /**
     * Define the public routes for the application.
     *
     * @return void
     */
    protected function publicRoutes()
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
