<?php

namespace munkireport\controller;

use \Controller, \View;
use munkireport\lib\Widgets;
use munkireport\lib\Dashboard;

class show extends Controller
{
    private $modules;
    public function __construct()
    {
        if (! $this->authorized()) {
            redirect('auth/login');
        }

        // Check for maintenance mode
        if(file_exists(APP_ROOT . 'storage/framework/down')) {
            redirect('error/client_error/503');
        }

        $this->modules = $modules = getMrModuleObj()->loadInfo();
    }

    public function index()
    {
        redirect('show/dashboard/default');
    }

    public function dashboard($which = '')
    {
        if($which == '')
        {
            redirect('show/dashboard/default');
        }
        $db = new Dashboard(conf('dashboard'));
        $db->render($which);
    }

    public function listing($module = '', $name = '')
    {
        if ($listing = $this->modules->getListing($module, $name)) {
            $data['page'] = 'clients';
            $data['scripts'] = array("clients/client_list.js");
            $viewpath = $listing->view_path;
            $view = $listing->view;
        } else {
            $data = array('status_code' => 404);
            $view = 'error/client_error';
            $viewpath = conf('view_path');
        }

        $obj = new View();
        $obj->view($view, $data, $viewpath);
    }

    public function report($module = '', $name = '')
    {
        $report = $this->modules->getReport($module, $name);

        if ( ! $report){
            $data = array('status_code' => 404);
            $view = 'error/client_error';
            $obj = new View();
            $obj->view(
                'error/client_error', 
                ['status_code' => 404]
            );
        }elseif ($report->type == 'php') {
            $obj = new View();
            $obj->view(
                $report->view, 
                [
                    'page' => 'clients',
                    'widget' => new Widgets(conf('widget')),
                ], 
                $report->view_path
            );
        }elseif ($report->type == 'yaml') {
            $db = new Dashboard([
                'search_paths' => [$report->view_path],
                'template' => env('DASHBOARD_TEMPLATE', 'dashboard/dashboard'),
                'default_layout' => [],           
            ]);
            $db->render($report->view);    
        }       
    }

    public function custom($which = 'default')
    {
        if ($which) {
            $data['args'] = func_get_args();
            $view = $which;
            $viewpath = APP_ROOT . 'custom/views/';
        } else {
            $data = array('status_code' => 404);
            $view = 'error/client_error';
            $viewpath = conf('view_path');
        }

        $obj = new View();
        $obj->view($view, $data, $viewpath);
    }
}
