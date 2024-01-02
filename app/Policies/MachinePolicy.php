<?php

namespace App\Policies;

use App\User;
use App\Machine;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response as AccessResponse;
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
     * Pre-authorization checks: Always allow roles which hold the `global` action (usually the admin role).
     * 
     * @param User $user
     * @param string $ability
     * @return bool|null
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdmin()) return true;
        return null;
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
     * - Nobody role cannot view anything. (Nobody is a user who is part of munkireport but belongs to no business units)
     *
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user): AccessResponse
    {
        // user must be admin or manager
        if (!config('_munkireport.enable_business_units', false)) return true;


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
     * @param User $user
     * @param Machine $machine
     * @return mixed
     */
    public function view(User $user, Machine $machine): AccessResponse
    {
        // there are no view restrictions when BU is turned off
        if (!config('_munkireport.enable_business_units', false)) return true;

        // Determine whether the user can view based on their machine groups which was decided by the MachineGroupMembership Auth Listener
        $machineGroups = session()->get('machine_groups', []);
        $matchMachineGroup = $machine->reportData()->firstOrFail()->machine_group;
        if (in_array((string)$matchMachineGroup, $machineGroups)) return true;


        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * With business units enabled:
     * - You need to be a manager in the same business unit *OR* a global admin.
     * With business units disabled:
     * - Be a global manager or admin
     *
     * @param User $user
     * @param Machine $machine
     * @return mixed
     */
    public function delete(User $user, Machine $machine): AccessResponse
    {
        if (!config('_munkireport.enable_business_units', false) && $user->isManager()) return true;

        $machineGroupBusinessUnits = DB::table('machine')
            ->join(
                'reportdata',
                'machine.serial_number', '=', 'reportdata.serial_number'
            )
            ->join(
                'machine_group',
                'reportdata.machine_group', '=', 'machine_group.groupid'
            )
            ->join(
                'business_unit', function (JoinClause $join) {
                    $join->on('reportdata.machine_group', '=', 'business_unit.value')
                        ->where('business_unit.property', '=', 'machine_group');
                })
            ->get();


        return false;
    }
}
