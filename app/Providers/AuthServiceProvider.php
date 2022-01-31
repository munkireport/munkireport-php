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

        // Archiving policy when using the Legacy \Reportdata_model class.
        'Reportdata_model' => 'App\Policies\ReportDataModelPolicy',
        'App\ReportData' => 'App\Policies\ReportDataModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('delete_machine', function (\App\User $user, string $serial_number) {
            if ($user->isAdmin()) return true;

            $authorizations = config('_munkireport.authorization', []);
            // No archive authorizations defined: it would not be possible to pass this gate.
            if (!isset($authorizations['delete_machine'])) {
                Log::debug('delete_machine gate always rejects access: no delete_machine authorizations are defined in the configuration file');
                return false;
            }

            if (!config('_munkireport.enable_business_units', false)) {
                $machineDeleters = $authorizations['delete_machine'];
                if (!in_array($user->role, $machineDeleters)) {
                    Log::debug('delete_machine gate rejected user: ' . $user->email . ', not in any role(s) that have delete_machine, business units NOT enabled');
                    return false;
                } else {
                    Log::debug('delete_machine gate accepted user: ' . $user->email . ', has role, , business units NOT enabled');
                    return true;
                }
            } else {
                // Consult the session-stored machine group membership for authorization
                $machineGroups = session()->get('machine_groups', []);
                $reportData = \App\ReportData::where('serial_number', $serial_number)->get();

                foreach ($reportData as $reportDatum) {
                    if (in_array($reportDatum->machine_group, $machineGroups)) return true;
                }
            }

            return false;
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
                Log::debug('global admin gate rejected user: ' . $user->email . ', has role `' . $user->role . '` which is not in any authorized role(s) (' . implode(', ', $globalAdmins) . ')');
                return false;
            }
        });
    }
}
