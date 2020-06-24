<?php

namespace Mr\Policies;

use Illuminate\Support\Facades\DB;
use Mr\MachineGroup;
use Mr\User;
use Mr\Machine;
use Illuminate\Auth\Access\HandlesAuthorization;

class MachinePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the machine.
     *
     * With business units disabled:
     * - Anyone can view.
     *
     * With business units enabled:
     * - Admins can always view.
     * - Manager or User can only view their own Business Unit.
     * - Nobody role cannot view anything.
     *
     * @param  \Mr\User  $user
     * @param  \Mr\Machine  $machine
     * @return mixed
     */
    public function view(User $user, Machine $machine)
    {
        // user must be admin or manager
        if (!config('munkireport.enable_business_units', false)) return true;
        if ($user->is_admin) return true;

        // TODO: BU
        return false;
    }

    /**
     * Determine whether the user can delete the machine.
     *
     * @param  \Mr\User  $user
     * @param  \Mr\Machine  $machine
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
