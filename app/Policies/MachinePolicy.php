<?php

namespace App\Policies;

use App\User;
use App\Machine;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\DB;

/**
 * MachinePolicy enforces rules on "machines" (which might be a combination of Machine + ReportData?) according
 * to the MunkiReport wiki, eg:
 *
 * - Without BU's, admins and managers can delete machines
 * - With BU's, admins can delete machines, managers can delete their own BU machines, and users can only view their own BU.
 *
 * @package App\Policies
 */
class MachinePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can view any Machines.
     *
     * With business units disabled:
     * - Anyone can view.
     *
     * With business units enabled:
     * - Admins can always view.
     * - Manager or User can only view their own Business Unit.
     * - Nobody role cannot view anything.
     *
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        // user must be admin or manager
        if (!config('_munkireport.enable_business_units', false)) return true;
        if ($user->isAdmin()) return true;

        // TODO: scan business unit membership

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * With business units enabled:
     * - Admins can always view.
     * - Manager or User can only view their own Business Unit.
     * - Nobody role cannot view anything.
     *
     * @param  \App\User  $user
     * @param  \App\Machine  $machine
     * @return mixed
     */
    public function view(User $user, Machine $machine)
    {
        // user must be admin or manager
        if (!config('_munkireport.enable_business_units', false)) return true;
        if ($user->isAdmin()) return true;

        // TODO: scan business unit membership

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Machine  $machine
     * @return mixed
     */
    public function delete(User $user, Machine $machine)
    {
        if ($user->is_admin) return true;

        $managerOfBusinessUnits = $user->managerOfBusinessUnits()->get('business_units.id');

        $managesMachineBusinessUnit = DB::table('machine')
            ->join(
                'reportdata',
                'machine.serial_number', '=', 'reportdata.serial_number'
            )
            ->join(
                'machine_groups',
                'reportdata.machine_group', '=', 'machine_groups.id'
            )
            ->join(
                'business_units',
                'machine_groups.business_unit_id', '=', 'business_units.id'
            )
            ->where('machine.id', '=', $machine->id)
            ->whereIn('business_units.id', $managerOfBusinessUnits);

        if ($managesMachineBusinessUnit->count() > 0) {
            return true;
        }

        return false;
    }
}
