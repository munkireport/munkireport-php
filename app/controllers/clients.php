<?php

namespace munkireport\controller;

use \Controller, \View;
use Doctrine\DBAL\Driver\PDOException;
use \Machine_model, \Reportdata_model, \Disk_report_model, \Warranty_model, \Localadmin_model, \Security_model;
use Mr\Contracts\TabController;


class clients extends Controller
{
    public function __construct()
    {
        if (! $this->authorized()) {
            redirect('auth/login');
        }

        // Connect to database
        $this->db();

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
        try {
            $obj = new View();

            if (authorized_for_serial($serial_number)) {
                $machine = new Machine_model;
                new Reportdata_model;
                new Disk_report_model;
                new Warranty_model;
                new Localadmin_model;
                new Security_model;

                $sql
                    = "SELECT m.*, r.console_user, r.long_username, r.remote_ip,
                        r.uptime, r.reg_timestamp, r.machine_group, r.timestamp,
			s.gatekeeper, s.sip, s.ssh_users, s.ard_users, s.firmwarepw, s.firewall_state, s.skel_state,
			w.purchase_date, w.end_date, w.status, l.users, d.totalsize, d.freespace,
                        d.smartstatus, d.encrypted
                FROM machine m
                LEFT JOIN reportdata r ON (m.serial_number = r.serial_number)
                LEFT JOIN security s ON (m.serial_number = s.serial_number)
                LEFT JOIN warranty w ON (m.serial_number = w.serial_number)
                LEFT JOIN localadmin l ON (m.serial_number = l.serial_number)
                LEFT JOIN diskreport d ON (m.serial_number = d.serial_number AND d.mountpoint = '/')
                WHERE m.serial_number = ?
                ";

                $obj->view('json', array('msg' => $machine->query($sql, $serial_number)));
            } else {
                $obj->view('json', array('msg' => array()));
            }
        } catch (\PDOException $exception) {
            $obj->view('json', array('msg' => Array(
                'error' => true,
                'error_message' => 'Database Exception: ' . $exception->getMessage(),
                'error_trace' => $exception->getTrace())));
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
        $machine = new Machine_model($sn);

        // Check if machine exists/is allowed for this user to view
        if (! $machine->id) {
            $view = $this->view('client/client_dont_exist');
        } else {
            $view = $this->view('client/client_detail');
        }

        $view->tabViews = [];
        $view->serial_number = $sn;

        // Tab list, each item should contain:
        //	'view' => path/to/tab
        // 'i18n' => i18n identifier matching a localised name
        // Optionally:
        // 'view_vars' => array with variables to pass to the views
        // 'badge' => id of a badge for this tab
        $tab_list = array(
            'summary' => array(
                'view' => 'summary_tab',
                'view_controller' => '\Mr\Core\Clients\SummaryTabController',
                'i18n' => 'client.tab.summary',
            ),
        );
//        $tab_list = Array();
        $view->tabs = Array(
            'summary' => array(
                'view' => 'summary_tab',
                'view_controller' => '\Mr\Core\Clients\SummaryTabController',
                'i18n' => 'client.tab.summary',
            ),
        );

        // Include modules tabs
        $modules = getMrModuleObj()->loadInfo();
        $modules->addTabs($tab_list);

        $tabViewParameters = Array('serial_number' => $sn);

        foreach ($tab_list as $id => $info) {
            if (isset($info['view_controller'])) {
                if (!isset($info['view_path'])) {  // Must be a core view

                } else {
                    require_once dirname($info['view_path']) . '/' . $info['view_controller'] . ".php";
                }

                $vc = new $info['view_controller'];
                $vc->render($view, $tabViewParameters);
                $view->tabViews[$info['view']] = $vc->viewName();
                $view->tabs[$id] = $info;
            } else { // View does not require a controller, so just add the blade template for inclusion.
                $view->tabViews[$info['view']] = $info['view'];
                $view->tabs[$id] = $info;
            }
        }

        // Add custom tabs
//        $tab_list = array_merge($tab_list, conf('client_tabs', array()));
//
//        $view->tab_list = $tab_list;

        echo $view->render();
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
