<?php

namespace App\Http\Controllers;

use Compatibility\BusinessUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use munkireport\models\Machine_group;

class UnitController extends Controller
{
    /**
     * Get unit data for current user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|object
     * @author
     */
    public function get_data(Request $request)
    {
        $out = array();

        // Initiate session
        $this->authorized();

        if ($request->session()->has('business_unit')) {
            // Get data for this unit
            $unit = new BusinessUnit;
            $out = $unit->all($request->session()->get('business_unit'));
        }

        return response()->json($out);
    }

    /**
     * Get machine group data for current user.
     *
     * This endpoint is different to the admin endpoint because it provides an "Unassigned" option. It is used to
     * populate the filter modal and inside machine_detail.js.
     *
     * @author
     **/
    public function get_machine_groups(Request $request)
    {
        $out = array();

        if ($request->session()->has('machine_groups')) {
            // Get data for this unit
            $mg = new Machine_group;
            foreach ($request->session()->get('machine_groups', []) as $group) {
                if ($mg_data = $mg->all($group)) {
                    $out[] = $mg->all($group);
                } else if ($group != 0 && count($request->session()->get('machine_groups', [])) != 0) // Not in Machine_group table
                {
                    $out[] = array(
                        'name' => 'Group '.$group,
                        'groupid' => $group);
                } else {
                    $out[] = array(
                        'name' => 'Unassigned',
                        'groupid' => $group);
                }
            }
        } else {
            $mg = new Machine_group;
            $out = $mg->all();
        }

        //Apply filter
        $groups = get_filtered_groups();
        foreach ($out as &$group) {
            $group['checked'] = in_array($group['groupid'], $groups);
        }

        usort($out, function($a, $b) {
            return strcasecmp($a['name'], $b['name']);
        });

        return response()->json($out);
    }
}
