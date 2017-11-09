<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::tokensCan([
            'users-read-self' => '',
            'users-read-all' => '',
            'users-read-emails' => '',
            'users-modify' => '',
            'users-orders-read' => '',
            'users-orders-modify' => '',
            'users-nodes-read' => '',
            'users-nodes-modify' => '',
            'organization-transactions-read' => '',
            'organization-transactions-modify' => '',
            'orders-read-all' => '',
            'nodes-read-all' => '',
            'producers-read-all' => '',
        ]);

        Passport::routes(null, ['middleware' => [\Barryvdh\Cors\HandleCors::class]]);
        Passport::enableImplicitGrant();
    }
}
