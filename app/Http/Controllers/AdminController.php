<?php

namespace App\Http\Controllers;

use App\ReportData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use MR\Kiss\ConnectDbTrait;
use munkireport\lib\BusinessUnit;
use munkireport\models\Business_unit;
use munkireport\models\Machine_group;


class AdminController extends Controller
{
    use ConnectDbTrait;

    public function __construct()
    {
        // Connect to database
        $this->connectDB();
    }

    /**
     * Save/Update a Machine Group
     *
     * Example Request Body:
     * - form-encoded
     * groupid=0&name=Group+0f&key=&business_unit=1
     *
     * If `groupid` is empty or null, create a new machine group.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function save_machine_group(Request $request): JsonResponse
    {
        Gate::authorize('global');


        if ($request->has('groupid')) {
            $machine_group = new Machine_group;
            $groupid = $request->input('groupid');

            // Empty groupid: create new
            if ($groupid === '' || is_null($groupid)) {
                $mg = new Machine_group;
                $groupid = $mg->get_max_groupid() + 1;
            }

            $out['groupid'] = intval($groupid);
            $props = $request->all(['business_unit', 'groupid', 'key', 'name']);
            foreach ($props as $property => $val) {
                // Skip groupid
                if ($property == 'groupid') {
                    continue;
                }

                // Update business unit membership
                if ($property == 'business_unit' && !empty($val)) {
                    Business_unit::updateOrCreate(
                        [
                            'property' => 'machine_group',
                            'value' => $groupid,
                        ],
                        [
                            'unitid' => $val,
                        ]
                    );
                    $out['business_unit'] = intval($val);
                    continue;
                }

                if (! is_array($val)) {
                    if ($val) {
                        $machine_group->id = '';
                        $machine_group->retrieveOne('groupid=? AND property=?', array($groupid, $property));
                        $machine_group->groupid = $groupid;
                        $machine_group->property = $property;
                        $machine_group->value = $val;
                        $machine_group->save();
                        $out[$property] = $val;
                    } else // Delete
                    {
                        $machine_group->deleteWhere('groupid=? AND property=?', array($groupid, $property));
                    }
                } else //array data
                {
                    $out['error'] = 'Unknown input: ' .$property;
                }
            }
            // Put key in array (for future purposes)
            if (isset($out['key'])) {
                $out['keys'][] = $out['key'];
                unset($out['key']);
            }
        } else {
            $out['error'] = 'Groupid is missing';
        }

        return jsonView($out, 200, false, true);
    }

    /**
     * Remove a Machine Group
     *
     * Request is form encoded and contains only the `groupid` field.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function remove_machine_group(Request $request): JsonResponse
    {
        Gate::authorize('global');

        $out = [];

        $groupid = $request->input('groupid', '');

        if ($groupid !== '') {
            $mg = new Machine_group;
            if ($out['success'] = $mg->deleteWhere('groupid=?', $groupid)) {
                // Delete from business unit
                $out['successs'] = Business_unit::where('property', 'machine_group')
                    ->where('value', $groupid)
                    ->delete();
            }
            // Reset group in report_data
            ReportData::where('machine_group', $groupid)
                ->update(['machine_group' => 0]);
        }

        return jsonView($out, 200, false, true);
    }

    //===============================================================

    /**
     * Save Business Unit
     *
     * @return void
     * @author
     **/
    public function save_business_unit(Request $request): JsonResponse
    {
        Gate::authorize('global');

        $unit = new BusinessUnit();
        return jsonView($unit->saveUnit($request->all([
            'unitid', 'name', 'address', 'link', 'iteminfo', 'managers', 'archivers', 'users'
            ])),
            200, false, true);
    }

    //===============================================================

    /**
     * remove_business_unit
     *
     * @return void
     * @author
     **/
    public function remove_business_unit(): JsonResponse
    {
        Gate::authorize('global');


        return jsonView([
            'success' => Business_unit::where('unitid', request('id', ''))->delete()
        ], 200, false, true);
    }

    //===============================================================


    /**
     * Return BU data for unitid or all units if unitid is empty
     *
     * Response is a JSON Array with elements in this format:
     *
     * {
     *   "0": {
     *       "users": [
     *           "user"
     *       ],
     *       "managers": [
     *           "@managers_group",
     *           "manager",
     *           "admin@localhost"
     *       ],
     *       "archivers": [
     *           "@archivers_group",
     *           "archiver"
     *       ],
     *       "machine_groups": [
     *           0,
     *           1,
     *           0
     *       ],
     *       "name": "IT Department",
     *       "unitid": 1
     *   }
     *
     *
     * @return void
     * @author
     **/
    public function get_bu_data(): JsonResponse
    {
        Gate::authorize('global');

        $out = [];
        $units = Business_unit::get()
            ->toArray();
        foreach ($units as $obj) {
            // Initialize
            $obj = (object) $obj;
            if (! isset($out[$obj->unitid])) {
                $out[$obj->unitid] = [
                    'users' => [],
                    'managers' => [],
                    'archivers' => [],
                    'machine_groups' => [],
                ];
            }
            switch ($obj->property) {
                case 'user':
                    $out[$obj->unitid]['users'][] = $obj->value;
                    break;
                case 'manager':
                    $out[$obj->unitid]['managers'][] = $obj->value;
                    break;
                case 'archiver':
                    $out[$obj->unitid]['archivers'][] = $obj->value;
                    break;
                case 'machine_group':
                    $out[$obj->unitid]['machine_groups'][] = intval($obj->value);
                    break;
                default:
                    $out[$obj->unitid][$obj->property] = $obj->value;
            }

            $out[$obj->unitid]['unitid'] = $obj->unitid;
        }

        return response()->json(array_values($out));
    }

    //===============================================================

    /**
     * Return Machinegroup data for groupid or all groups if groupid is empty.
     *
     * Example response (no groupid) Array containing elements like:
     * {
     *  ...
     *   "1": {
     *     "keys": [
     *       "1448859D-1EA0-DD43-7C9C-605238328F3E"
     *     ],
     *     "groupid": 1,
     *     "name": "Example Machine Group"
     *     }
     *   }
     * }
     **/
    public function get_mg_data(string $groupid = ""): JsonResponse
    {
        Gate::authorize('global');

        $out = [];

        // Get created Machine Groups
        $mg = new Machine_group;
        foreach ($mg->all($groupid) as $arr) {
            $out[$arr['groupid']] = $arr;
        }

        // Get registered machine groups
        $result = ReportData::selectRaw('machine_group, COUNT(*) AS cnt')
            ->groupBy('machine_group')
            ->get()
            ->toArray();
        foreach ($result as $obj) {
            if (! isset($out[$obj['machine_group']])) {
                $out[$obj['machine_group']] = [
                    'groupid' => $obj['machine_group'],
                    'name' => 'Group '.$obj['machine_group'],
                ];
            }
            $out[$obj['machine_group']]['cnt'] = $obj['cnt'];
        }

        return response()->json(array_values($out));
    }

    //===============================================================

    public function show($which = '')
    {
        Gate::authorize('global');

        if ($which) {
            $data['page'] = 'clients';
            $data['scripts'] = array("clients/client_list.js");
            $view = 'admin/'.$which;
        } else {
            $data = array('status_code' => 404);
            $view = 'error/client_error';
        }

        return view('admin.business_units', $data);
    }
}
