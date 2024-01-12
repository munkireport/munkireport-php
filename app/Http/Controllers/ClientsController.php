<?php

namespace App\Http\Controllers;

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

    public function index()
    {

        $data['page'] = 'clients';

        mr_view('client/client_list', $data);
    }

    /**
     * Get some data for serial_number
     *
     * @author AvB
     **/
    public function get_data($serial_number = '')
    {
        if (authorized_for_serial($serial_number)) {
            $machine = new \Model;

            $sql = "SELECT m.computer_name, r.remote_ip, r.archive_status as status, n.ipv4ip, n.ipv6ip
                FROM machine m
                LEFT JOIN network n ON (m.serial_number = n.serial_number)
                LEFT JOIN reportdata r ON (m.serial_number = r.serial_number)
                WHERE m.serial_number = ? ORDER BY ipv4ip DESC LIMIT 1
                ";

            jsonView($machine->query($sql, $serial_number));
        } else {
            jsonError('Not authorized for serial number', 403, false);
        }
    }

    /**
     * Retrieve links from config
     *
     * @author
     **/
    public function get_links()
    {
        $out = array();
        if (config('munkireport.vnc_link')) {
            $out['vnc'] = config('munkireport.vnc_link');
        }
        if (config('munkireport.ssh_link')) {
            $out['ssh'] = config('munkireport.ssh_link');
        }

        jsonView($out);
    }

    // ------------------------------------------------------------------------

    /**
     * Detail page of a machine
     *
     * @param string serial
     * @return void
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
            mr_view("client/client_dont_exist", $data);
        } else {
            mr_view("client/client_detail", $data);
        }
    }

    // ------------------------------------------------------------------------

    /**
     * List of machines
     *
     * @param string name of view
     * @return void
     * @author abn290
     **/
    public function show($view = '')
    {
        $data['page'] = 'clients';
        // TODO: Check if view exists
        mr_view('client/'.$view, $data);
    }
}
