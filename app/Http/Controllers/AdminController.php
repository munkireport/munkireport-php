<?php

namespace App\Http\Controllers;

use App\ReportData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Compatibility\Kiss\ConnectDbTrait;
use Compatibility\Service\BusinessUnit;
use Compatibility\BusinessUnit as CompatibleBusinessUnit;
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
     * groupid=0&name=Group+0f&key=<generated uuid or empty>&business_unit=1
     *
     * If `groupid` is empty or null, create a new machine group.
     * If `groupid` is not present, you will get an error, but with http status 200
     *
     * Response:
     * {"groupid":2,"business_unit":1,"name":"mgnew","keys":["215C10D0-648E-6E86-841C-4B27EBA535F8"]}
     *
     * @OA\Post(
     *  path="/admin/save_machine_group",
     *  summary="create/update machine group",
     *  tags={"internal-v5-machine-groups"},
     *  @OA\RequestBody(
     *     required=true,
     *     request="createUpdateBusinessUnitRequest",
     *     @OA\MediaType(
     *      mediaType="application/x-www-form-urlencoded",
     *      @OA\Schema(
     *        type="object",
     *        @OA\Property(
     *            property="groupid",
     *            type="integer",
     *            description="A machine group id to update, or empty for create",
     *        ),
     *        @OA\Property(
     *            property="name",
     *            type="string",
     *            description="machine group name",
     *        ),
     *        @OA\Property(
     *            property="key",
     *            type="string",
     *            format="uuid",
     *            description="machine group uuid key, or empty to generate one",
     *        ),
     *        @OA\Property(
     *            property="business_unit",
     *            type="integer",
     *            format="int32",
     *            description="business unit id which will contain this machine group",
     *        ),
     *     ),
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="successful operation",
     *     @OA\JsonContent(
     *        @OA\Property(
     *            property="groupid",
     *            type="integer",
     *            description="A machine group id to update, or empty for create",
     *        ),
     *        @OA\Property(
     *            property="name",
     *            type="string",
     *            description="machine group name",
     *        ),
     *        @OA\Property(
     *            property="business_unit",
     *            type="integer",
     *            format="int32",
     *            description="business unit id which will contain this machine group",
     *        ),
     *        @OA\Property(
     *            property="keys",
     *            type="array",
     *            @OA\Items(
     *              type="string",
     *              format="uuid",
     *              description="machine group uuid key, or empty to generate one",
     *            ),
     *        ),
     *    ),
     *  ),
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function save_machine_group(Request $request): JsonResponse
    {
        Gate::authorize('global');

        if (!$request->has('groupid')) {
            return response()->json([
                'error' => 'Groupid is missing',
            ]);
        }

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
                CompatibleBusinessUnit::updateOrCreate(
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
                    //$machine_group->id = '';
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

        return response()->json($out);
    }

    /**
     * Remove a Machine Group
     *
     * Request is form encoded and contains only the `groupid` field.
     * can also be a route parameter???
     *
     * @OA\Get(
     *  path="/admin/remove_machine_group/{groupid}",
     *  summary="Remove machine group",
     *  tags={"internal-v5-machine-groups"},
     *  @OA\Parameter(
     *     name="groupid",
     *     in="path",
     *     description="The machine group id to delete",
     *     required=true,
     *  ),
     *  @OA\Response(
     *     response="200",
     *     description="successful operation",
     *     @OA\JsonContent(
     *      @OA\Property(
     *          property="success",
     *          type="boolean",
     *          description="delete operation was successful",
     *      ),
     *      @OA\Property(
     *          property="successs",
     *          type="integer",
     *          format="int32",
     *          description="value indicates business unit the machine group was removed from",
     *      ),
     *    ),
     *  ),
     * )
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function remove_machine_group(Request $request, ?int $groupid): JsonResponse
    {
        Gate::authorize('global');

        $out = [];

        $id = $groupid ?? $request->input('groupid', '');

        if ($id !== '') {
            $mg = new Machine_group;
            if ($out['success'] = $mg->deleteWhere('groupid=?', $id)) {
                // Delete from business unit
                $out['successs'] = CompatibleBusinessUnit::where('property', 'machine_group')
                    ->where('value', $id)
                    ->delete();
            }
            // Reset group in report_data
            ReportData::where('machine_group', $id)
                ->update(['machine_group' => 0]);
        }

        return response()->json($out);
    }

    //===============================================================

    /**
     * Save Business Unit and optionally create/update child machine groups.
     *
     * @OA\Post(
     *  path="/admin/save_business_unit",
     *  summary="Create/Update a Business Unit",
     *  tags={"internal-v5-business-units"},
     *  @OA\RequestBody(
     *     required=true,
     *     request="createUpdateBusinessUnitRequest",
     *     @OA\MediaType(
     *      mediaType="application/x-www-form-urlencoded",
     *      @OA\Schema(
     *        type="object",
     *        @OA\Property(
     *            property="users",
     *            type="array",
     *            description="A list of users and groups with the user role (denoted by @ prefix). A literal value of hash `#` means empty array",
     *            @OA\Items(
     *                type="string"
     *            )
     *        ),
     *        @OA\Property(
     *            property="managers",
     *            type="array",
     *            description="A list of users and groups with the manager role. A literal value of hash `#` means empty array",
     *            @OA\Items(
     *                type="string"
     *            )
     *        ),
     *        @OA\Property(
     *            property="archivers",
     *            type="array",
     *            description="A list of users and groups with the archiver role. A literal value of hash `#` means empty array",
     *            @OA\Items(
     *                type="string"
     *            )
     *        ),
     *        @OA\Property(
     *            property="machine_groups",
     *            type="array",
     *            description="A list of machine group IDs that are part of this business unit",
     *            @OA\Items(
     *                type="string",
     *                format="int32",
     *            )
     *        ),
     *        @OA\Property(
     *            property="name",
     *            type="string",
     *            description="The name of the business unit",
     *            example="Sales and Marketing",
     *        ),
     *        @OA\Property(
     *            property="unitid",
     *            type="integer",
     *            format="int32",
     *            description="Business Unit ID",
     *        ),
     *        @OA\Property(
     *            property="address",
     *            type="string",
     *            description="Street address",
     *        ),
     *        @OA\Property(
     *            property="link",
     *            type="string",
     *            format="url",
     *            description="URL",
     *        ),
     *        @OA\Property(
     *            property="groupid",
     *            type="string",
     *            description="groupid",
     *            example="1",
     *        ),
     *        @OA\Property(
     *            property="key",
     *            type="string",
     *            format="uuid",
     *            description="A machine group key associated with the business unit",
     *        ),
     *        @OA\Property(
     *            property="iteminfo",
     *            type="array",
     *            description="array of machine groups that should be created or updated to be child objects of this BU",
     *            @OA\Items(
     *              type="object",
     *              @OA\Property(
     *                  property="key",
     *                  type="string",
     *                  description="A machine group id. If empty, a machine group will be created under this BU",
     *              ),
     *              @OA\Property(
     *                  property="name",
     *                  type="string",
     *                  description="A machine group name to create (or update if the key was not empty)",
     *              ),
     *           ),
     *          ),
     *        ),
     *     ),
     *  ),
     *  @OA\Response(
     *     response="200",
     *     description="successful operation",
     *  ),
     * )
     **/
    public function save_business_unit(Request $request): JsonResponse
    {
        Gate::authorize('global');

        $unit = new BusinessUnit();
        $out = $unit->saveUnit($request->all([
            'unitid', 'name', 'address', 'link', 'managers', 'archivers', 'users', 'groupid', 'key', 'keys', 'machine_groups',
            'iteminfo' // Contains an array of new machine groups to be created
        ]));

        return response()->json($out);
    }

    //===============================================================

    /**
     * Remove a business unit
     *
     * @OA\Post(
     *  path="/admin/remove_business_unit",
     *  summary="Remove a business unit",
     *  tags={"internal-v5-business-units"},
     *  @OA\RequestBody(
     *    required=true,
     *    request="removeBusinessUnitRequest",
     *    @OA\MediaType(
     *     mediaType="application/x-www-form-urlencoded",
     *     @OA\Schema(
     *      type="object",
     *      @OA\Property(
     *        property="id",
     *        type="string",
     *        description="The business unit id to delete",
     *      ),
     *     ),
     *    ),
     *  ),
     *  @OA\Response(
     *     response="200",
     *     description="successful operation",
     *     @OA\JsonContent(
     *      @OA\Property(
     *          property="success",
     *          type="integer",
     *          format="int32",
     *          description="TODO",
     *      ),
     *     ),
     *  ),
     * )
     **/
    public function remove_business_unit(): JsonResponse
    {
        Gate::authorize('global');

        $success = CompatibleBusinessUnit::where('unitid', request('id', ''))->delete();

        return response()->json([
            'success' => $success,
        ]);
    }

    //===============================================================


    /**
     * Return BU data for unitid or all units if unitid is empty
     *
     * @todo This is currently not fully compatible with MunkiReport 5.6.5 because it does not return empty strings for
     *       unset values, or empty arrays for unset collections.
     *
     * @OA\Get(
     *     path="/admin/get_bu_data",
     *     summary="Get a list of Business Units",
     *     tags={"internal-v5-business-units"},
     *     @OA\Response(
     *      response="200",
     *      description="successful operation",
     *      @OA\JsonContent(
     *          @OA\Property(
     *              type="array",
     *              description="List of business units",
     *              @OA\Items(
     *                  type="object",
     *                  @OA\Property(
     *                      property="users",
     *                      type="array",
     *                      description="A list of users and groups with the user role (denoted by @ prefix)",
     *                      @OA\Items(
     *                          type="string"
     *                      )
     *                  ),
     *                  @OA\Property(
     *                      property="managers",
     *                      type="array",
     *                      description="A list of users and groups with the manager role",
     *                      @OA\Items(
     *                          type="string"
     *                      )
     *                  ),
     *                  @OA\Property(
     *                      property="archivers",
     *                      type="array",
     *                      description="A list of users and groups with the archiver role",
     *                      @OA\Items(
     *                          type="string"
     *                      )
     *                  ),
     *                  @OA\Property(
     *                      property="machine_groups",
     *                      type="array",
     *                      description="A list of machine group IDs that are part of this business unit",
     *                      @OA\Items(
     *                          type="integer",
     *                          format="int32",
     *                      )
     *                  ),
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      description="The name of the business unit",
     *                      example="Sales and Marketing",
     *                  ),
     *                  @OA\Property(
     *                      property="unitid",
     *                      type="integer",
     *                      format="int32",
     *                      description="Business Unit ID",
     *                  ),
     *                  @OA\Property(
     *                      property="address",
     *                      type="string",
     *                      description="Street address",
     *                  ),
     *                  @OA\Property(
     *                      property="link",
     *                      type="string",
     *                      format="url",
     *                      description="URL",
     *                  ),
     *                  @OA\Property(
     *                      property="groupid",
     *                      type="string",
     *                      description="groupid",
     *                      example="1",
     *                  ),
     *                  @OA\Property(
     *                      property="key",
     *                      type="string",
     *                      format="uuid",
     *                      description="A machine group key associated with the business unit",
     *                  ),
     *              ),
     *          ),
     *     ),
     *     )
     * )
     **/
    public function get_bu_data(): JsonResponse
    {
        Gate::authorize('global');

        $out = [];
        $units = CompatibleBusinessUnit::get()
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
                    'name' => '',
                    'unitid' => 0,
                    'address' => '',
                    'link' => '',
                    'groupid' => '',
                    'key' => '',
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
     * @OA\Get(
     *     path="/admin/get_mg_data",
     *     summary="Get a list of Machine Groups",
     *     tags={"internal-v5-machine-groups"},
     *     @OA\Parameter(
     *      name="groupid",
     *      in="query",
     *      description="A specific machine group id to retrieve",
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="successful operation",
     *      @OA\JsonContent(
     *          @OA\Property(
     *              type="array",
     *              description="List of business units",
     *              @OA\Items(
     *                  type="object",
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      description="The name of the machine group",
     *                      example="Laptops",
     *                  ),
     *                  @OA\Property(
     *                      property="groupid",
     *                      type="integer",
     *                      format="int32",
     *                      description="machine group ID",
     *                  ),
     *                  @OA\Property(
     *                      property="keys",
     *                      type="array",
     *                      @OA\Items(
     *                          type="string",
     *                          format="uuid",
     *                          description="A machine group key which can be used to identify a client as belonging to this group",
     *                      )
     *                  ),
     *              ),
     *          ),
     *     ),
     *     )
     * )
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
