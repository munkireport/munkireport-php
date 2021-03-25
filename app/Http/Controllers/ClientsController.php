<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Machine_model;
use MR\Kiss\ConnectDbTrait;

class ClientsController extends Controller
{
    use ConnectDbTrait;

    public function __construct()
    {
        if (!Str::contains(config('auth.methods'), 'NOAUTH')) {
            $this->middleware('auth');
        }

        // Connect to database
        $this->connectDB();

    }

    /**
     * Get some data for serial_number
     *
     * @author AvB
     **/
    public function get_data($serial_number = ''): JsonResponse
    {
        if (authorized_for_serial($serial_number)) {
            $machine = new \Model;

            $sql = "SELECT m.computer_name, r.remote_ip, r.archive_status as status, n.ipv4ip, n.ipv6ip
                FROM machine m
                LEFT JOIN network n ON (m.serial_number = n.serial_number)
                LEFT JOIN reportdata r ON (m.serial_number = r.serial_number)
                WHERE m.serial_number = ? ORDER BY ipv4ip DESC LIMIT 1
                ";

            return response()->json($machine->query($sql, $serial_number));
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     * @author abn290
     **/
    public function detail($sn = '')
    {
        $data = array('serial_number' => $sn);
        $data['scripts'] = array("clients/client_detail.js");


        $machine = Machine_model::where('serial_number', $sn)
            ->first();

        // Check if machine exists/is allowed for this user to view
        if (! $machine) {
            return view("client.client_dont_exist", $data);
        } else {

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
            $modules = getMrModuleObj()->loadInfo();
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
}
