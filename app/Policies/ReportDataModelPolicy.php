<?php

namespace App\Policies;

use App\ReportData;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;
use MR\BusinessUnit;

class ReportDataModelPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the current user may archive the given machine (via report_data table).
     *
     * In MunkiReport 5.6.5, to be part of the `archiver` role you must have met these criteria:
     *
     * Without Business Units:
     * - Your username was listed in ROLES_ARCHIVER or config roles.archiver OR
     * - The archiver roles contained '*' which meant that everyone had the archiver role (not a supported case) OR
     * - The ROLES_ARCHIVER / roles.archiver array contained a group (a string prefixed with '@') that you were part of,
     *   Determined by $_SESSION['groups'] that was populated from logging in earlier.
     *
     * If you had Business Units enabled, these criteria applied in addition to the above:
     * - If your username was assigned the role of `archiver` for *ONE* business unit, you had the archiver role.
     * - If your username was assigned many roles, including `archiver`, for *ONE* business unit, the result was random.
     * - Multiple Business Unit assignment was not possible in 5.6.5, so the query would never return multiple BUs.
     * - If a Business Unit contained a group (Using the '@' string prefix), and the group was assigned the `archiver`
     *   role. You were assigned the archiver role.
     * - If multiple Business Units contained a group that you were in, The first result won.
     * - If you were in multiple groups that were a part of multiple business units, the first group in your membership
     *   list took precedence. This could be random depending on the login method.
     *
     * The Business Unit you were allowed to `archive` for, would be determined by the session variable $_SESSION['business_unit']
     *
     * In MunkiReport 5.6.5, to `archive` machines you must have met these criteria:
     *
     * - You have a role
     * - The role is listed within the config item `authorization.archive`. The archiver role is determined using the process
     *   above.
     *
     * @param User $user
     * @param ReportData $reportData
     */
    public function archive(User $user, ReportData $reportData) {
        if (empty($user->role)) return Response::deny('A user without any roles cannot perform the `archive` action.');
        $authorizedRoles = config('_munkireport.authorization.archive', []);

        if (!config('_munkireport.enable_business_units', false)) {
            if (in_array($user->role, $authorizedRoles)) {
                return Response::allow('Archive access allowed, reason: The role `' . $user->role . '` is listed for archive authorization');
            }

            return Response::deny('The current user is not a part of the roles list for the `archive` authorization');
        } else {
            $businessUnitId = session()->get('business_unit');

            // Grab business units where `machine_group` property has value = $reportData->machine_group
            $businessUnitsViaMachineGroups = BusinessUnit::query()->where('property', '=',BusinessUnit::PROP_MACHINE_GROUP)
                ->where('value', '=', $reportData->machine_group)
                ->get();

            foreach ($businessUnitsViaMachineGroups as $businessUnit) {
                if ($businessUnitId === $businessUnit->unitid && in_array($user->role, $authorizedRoles)) {
                    return Response::allow(
                        'Archive access allowed, reason: The role `' . $user->role . '` is listed for archive authorization and the user is a member of Business Unit ID ' . $businessUnitId);
                }
            }
            return Response::deny('The current user is not a part of any business unit which has them listed as an `archiver`, or the user is attempting to archive a machine that is not in their business unit');
        }
    }


    /**
     * Archive all machines that meet a certain criteria (expired time).
     *
     * Because there is no specific `ReportData` model to process, we just need to know whether the user
     * has `archiver` role/authorization of anything.
     *
     * Process is similar to `archive` authorization but we cannot check machine groups if business units are enabled.
     *
     * @param User $user
     */
    public function archive_bulk(User $user)
    {
        return Response::allow();
    }

    /**
     * Perform pre-authorization checks.
     *
     * @param  \App\User  $user
     * @param  string  $ability
     * @return void|bool
     */
    public function before(User $user, $ability)
    {
        if ($user->role === 'admin') {
            return true;
        }
    }

    /**
     * This may be removed and serves as an example of a Gate-style archive authorization
     *
     * @param User $user
     */
    public function archive_gate(User $user)
    {
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
    }
}
