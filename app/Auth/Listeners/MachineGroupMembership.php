<?php


namespace App\Auth\Listeners;

use App\User, \Reportdata_model;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;
use munkireport\models\Business_unit;
use munkireport\models\Machine_group;

/**
 * MachineGroupMembership listens for successful logins and then decides, using the same rules that AuthHandler::setSessionProps
 * used to have, which machine groups the user should get for this session.
 *
 * @package App\Auth\Listeners
 */
class MachineGroupMembership
{
    public function handle(Login $event) {
        Log::info('evaluating machinegroup memberships');
        if( session()->has('business_unit')){
            // Only retrieve machinegroups for this business unit
            $businessUnitId = session()->get('business_unit');
            $machineGroups = Business_unit::where('unitid', $businessUnitId)
            ->where('property', 'machine_group')
            ->get()
            ->pluck('value')
            ->toArray();
        }
        else{
            // Can access all defined groups (from machine_group)
            // and used groups (from reportdata)
            $mg = new Machine_group;
            $reportedMachineGroups = Reportdata_model::select('machine_group')
                ->groupBy('machine_group')
                ->get()
                ->pluck('machine_group')
                ->toArray();
            $reportedMachineGroups = $reportedMachineGroups ? $reportedMachineGroups : [0];
            $machineGroups = array_unique(array_merge($reportedMachineGroups, $mg->get_group_ids()));
        }
        Log::info('found machinegroup memberships: ', $machineGroups);
        session()->put('machine_groups', $machineGroups);
    }
}