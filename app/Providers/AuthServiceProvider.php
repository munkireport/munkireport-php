<?php

namespace App\Providers;

use App\Auth\MultiAuthGuard;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::extend('multi', function ($app, $name, array $config) {
            $authMethods = explode(',', config('auth.methods'));
            return new MultiAuthGuard(Auth::createUserProvider($config['provider']), $authMethods);
        });
    }
}
