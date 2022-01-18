<?php

namespace App\Http\Controllers;

use App\Machine;
use App\ReportData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use munkireport\lib\Modules;
use munkireport\models\Machine_model;
use MR\Kiss\ConnectDbTrait;

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
                    ->setStatusCode(403)
                    ->json(['error' => 'Not authorized for serial number']);
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
     * @param string serial
     * @author abn290
     **/
    public function detail(string $sn = '')
    {
        $data = array('serial_number' => $sn);
        $data['scripts'] = array("clients/client_detail.js");


        $machine = Machine_model::where('serial_number', $sn)
            ->firstOrFail();

        $reportData = ReportData::where('serial_number', $sn)
            ->firstOrFail();
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
}
