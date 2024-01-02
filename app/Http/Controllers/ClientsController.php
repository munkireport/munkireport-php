<?php

namespace App\Http\Controllers;

use App\Machine;
use App\ReportData;
use Illuminate\Http\JsonResponse;
use munkireport\lib\Modules;
use Compatibility\Kiss\ConnectDbTrait;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    use ConnectDbTrait;

    public function __construct()
    {
        // Connect to database
        $this->connectDB();
    }

    /**
     * Get some data for serial_number
     *
     * @OA\Get(
     *   path="/clients/get_data/{serial_number}",
     *   summary="Get data for a single client",
     *   tags={"internal-v5-clients"},
     *   @OA\Parameter(
     *     name="serial_number",
     *     in="path",
     *     description="Serial number of the machine to fetch",
     *     required=true,
     *   ),
     *   @OA\Response(
     *     response="403",
     *     description="not authorized",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="error",
     *         type="string",
     *         description="error message",
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="successful operation",
     *     @OA\JsonContent(
     *       type="array",
     *       @OA\Items(
     *         type="object",
     *         @OA\Property(
     *           property="computer_name",
     *           type="string",
     *           description="computer name unqualified",
     *         ),
     *         @OA\Property(
     *           property="remote_ip",
     *           type="string",
     *           description="IP address that contacted munkireport to send the data",
     *         ),
     *         @OA\Property(
     *           property="status",
     *           type="number",
     *           description="number representing the archival status of the client",
     *         ),
     *         @OA\Property(
     *           property="ipv4ip",
     *           type="string",
     *           description="IPv4 address of the client",
     *         ),
     *         @OA\Property(
     *           property="ipv6ip",
     *           type="string",
     *           description="IPv6 address of the client",
     *         ),
     *       )
     *     )
     *   )
     * )
     *
     * @author AvB
     **/
    public function get_data(string $serial_number): JsonResponse
    {
        if (authorized_for_serial($serial_number)) {
            $machine = Machine::with('reportdata', 'network')
                ->where('serial_number', $serial_number)
                ->firstOrFail();

            $out = [
                'computer_name' => $machine->computer_name,
                'remote_ip' => $machine->reportData->remote_ip,
                'status' => $machine->reportData->archive_status,
                'ipv4ip' => $machine->network ? $machine->network->ipv4ip : null,
                'ipv6ip' => $machine->network ? $machine->network->ipv6ip : null,
            ];
            // The response is wrapped in an array to keep backwards compatibility with older javascript.
            // Newer front end code should not use /clients/get_data
            return response()->json([$out]);
        } else {
            return response()
                    ->json(['error' => 'Not authorized for serial number'], 403);
        }
    }

    /**
     * Retrieve links from config
     *
     * @author
     **/
    public function get_links(): JsonResponse
    {
        $out = array();
        if (config('_munkireport.vnc_link')) {
            $out['vnc'] = config('_munkireport.vnc_link');
        }
        if (config('_munkireport.ssh_link')) {
            $out['ssh'] = config('_munkireport.ssh_link');
        }

        return response()->json($out);
    }

    // ------------------------------------------------------------------------

    /**
     * Detail page of a machine
     *
     * @param string $sn serial number
     * @author abn290
     **/
    public function detail(Request $request, string $sn = '')
    {
        $data = array('serial_number' => $sn);
        $data['scripts'] = array("clients/client_detail.js");


        $machine = Machine::where('serial_number', $sn)
            ->firstOrFail();

        $reportData = ReportData::where('serial_number', $sn)
            ->firstOrFail();

        if ($request->user()->cannot('view', $machine)) {
            abort(403);
        }

        $data['reportData'] = $reportData;

        // Tab list, each item should contain:
        //	'view' => path/to/tab
        // 'i18n' => i18n identifier matching a localised name
        // Optionally:
        // 'view_vars' => array with variables to pass to the views
        // 'badge' => id of a badge for this tab
        $tab_list = [
            'summary' => [
                'view' => 'client/summary_tab',
                'view_vars' => [
                    'widget_list' => [],
                ],
                'i18n' => 'client.tab.summary',
            ],
        ];

        // Include module tabs
        $modules = app(Modules::class)->loadInfo();
        $modules->addTabs($tab_list);

        // Add custom tabs
        $tab_list = array_merge($tab_list, conf('client_tabs', []));


        // Add widgets to summary tab
        $modules->addWidgets(
            $tab_list['summary']['view_vars']['widget_list'],
            conf('detail_widget_list', [])
        );

        $data['tab_list'] = $tab_list;

        return view("clients.detail", $data);

    }

    /**
     * Show a basic page guiding the admin to install a client.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|null
     */
    public function install()
    {
        return view('clients.install');
    }
}
