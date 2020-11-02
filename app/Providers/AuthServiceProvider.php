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
        // These policies implement the Authorization behaviour specified in the wiki article about access to delete/view
        'App\Machine' => 'App\Policies\MachinePolicy',
        'App\BusinessUnit' => 'App\Policies\BusinessUnitPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('archive', function ($user) {
            foreach (config('_munkireport.authorization', []) as $item => $roles) {
                if ($item === 'archive') {
                    return in_array($user->role, $roles);
                }
            }

            // Role not found: unauthorized!
            return false;
        });

        Gate::define('delete_machine', function ($user) {
            foreach (config('_munkireport.authorization', []) as $item => $roles) {
                if ($item === 'delete_machine') {
                    return in_array($user->role, $roles);
                }
            }

            // Role not found: unauthorized!
            return false;
        });

        Gate::define('global', function ($user) {
            foreach (config('_munkireport.authorization', []) as $item => $roles) {
                if ($item === 'global') {
                    return in_array($user->role, $roles);
                }
            }

            // Role not found: unauthorized!
            return false;
        });
    }
}
