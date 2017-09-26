<?php

namespace munkireport\controller;

use \Controller, \View;
use \Machine_model, \Reportdata_model, \Disk_report_model, \Warranty_model, \Localadmin_model, \Security_model;



class clients extends Controller
{
    public function __construct()
    {
        if (! $this->authorized()) {
            redirect('auth/login');
        }
    }

    public function index()
    {

        $data['page'] = 'clients';

        $obj = new View();
        $obj->view('client/client_list', $data);
    }

    /**
     * Get some data for serial_number
     *
     * @author AvB
     **/
    public function get_data($serial_number = '')
    {
        $obj = new View();

        if (authorized_for_serial($serial_number)) {
            $machine = new Machine_model;
            new Reportdata_model;
            new Disk_report_model;
            new Warranty_model;
            new Localadmin_model;
            new Security_model;

            $sql = "SELECT m.*, r.console_user, r.long_username, r.remote_ip,
                        r.uptime, r.reg_timestamp, r.machine_group, r.timestamp,
			s.gatekeeper, s.sip, s.ssh_users, s.ard_users, s.firmwarepw, s.firewall_state, s.skel_state,
			w.purchase_date, w.end_date, w.status, l.users, d.TotalSize, d.FreeSpace,
                        d.SMARTStatus, d.CoreStorageEncrypted
                FROM machine m
                LEFT JOIN reportdata r ON (m.serial_number = r.serial_number)
                LEFT JOIN security s ON (m.serial_number = s.serial_number)
                LEFT JOIN warranty w ON (m.serial_number = w.serial_number)
                LEFT JOIN localadmin l ON (m.serial_number = l.serial_number)
                LEFT JOIN diskreport d ON (m.serial_number = d.serial_number AND d.MountPoint = '/')
                WHERE m.serial_number = ?
                ";

            $obj->view('json', array('msg' => $machine->query($sql, $serial_number)));
        } else {
            $obj->view('json', array('msg' => array()));
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
        if (conf('vnc_link')) {
            $out['vnc'] = conf('vnc_link');
        }
        if (conf('ssh_link')) {
            $out['ssh'] = conf('ssh_link');
        }

        $obj = new View();
        $obj->view('json', array('msg' => $out));
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

        $obj = new View();

        $machine = new Machine_model($sn);

        // Check if machine exists/is allowed for this user to view
        if (! $machine->id) {
            $obj->view("client/client_dont_exist", $data);
        } else {
            $obj->view("client/client_detail", $data);
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
        $obj = new View();
        $obj->view('client/'.$view, $data);
    }
}
