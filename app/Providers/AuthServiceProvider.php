<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
            $authorizations = config('_munkireport.authorization', []);
            // No archive authorizations defined: it would not be possible to pass this gate.
            if (!isset($authorizations['archive'])) {
                Log::debug('archive gate always rejects access: no archive authorizations are defined in the configuration file');
                return false;
            }

            $archivers = $authorizations['archive'];
            if (in_array($user->role, $archivers)) {
                Log::debug('archive gate accepted user: ' . $user->email . ', has role');
                return true;
            } else {
                Log::debug('archive gate rejected user: ' . $user->email . ', not in any role(s) that have archive');
                return false;
            }
        });

        Gate::define('delete_machine', function ($user) {
            $authorizations = config('_munkireport.authorization', []);
            // No archive authorizations defined: it would not be possible to pass this gate.
            if (!isset($authorizations['delete_machine'])) {
                Log::debug('delete_machine gate always rejects access: no delete_machine authorizations are defined in the configuration file');
                return false;
            }

            $machineDeleters = $authorizations['delete_machine'];
            if (in_array($user->role, $machineDeleters)) {
                Log::debug('delete_machine gate accepted user: ' . $user->email . ', has role');
                return true;
            } else {
                Log::debug('delete_machine gate rejected user: ' . $user->email . ', not in any role(s) that have delete_machine');
                return false;
            }
        });

        Gate::define('global', function ($user) {
            $authorizations = config('_munkireport.authorization', []);
            // No archive authorizations defined: it would not be possible to pass this gate.
            if (!isset($authorizations['global'])) {
                Log::debug('archive gate always rejects access: no global admin authorizations are defined in the configuration file');
                return false;
            }

            $globalAdmins = $authorizations['global'];
            if (in_array($user->role, $globalAdmins)) {
                Log::debug('global admin gate accepted user: ' . $user->email . ', has role');
                return true;
            } else {
                Log::debug('global admin gate rejected user: ' . $user->email . ', not in any role(s) that have archive');
                return false;
            }
        });
    }
}
