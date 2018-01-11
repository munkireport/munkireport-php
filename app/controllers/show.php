<?php

namespace munkireport\controller;

use \Controller, \View;
use munkireport\lib\Widgets;

class show extends Controller
{
    private $modules;
    public function __construct()
    {
        if (! $this->authorized()) {
            redirect('auth/login');
        }

        $this->modules = $modules = getMrModuleObj()->loadInfo();
    }

    public function index()
    {
        redirect('show/dashboard');
    }

    public function dashboard($which = '')
    {
        // $data['widget'] = new Widgets();

        if ($which) {
            $view = 'dashboard/'.$which;
        } else {
            if (file_exists(VIEW_PATH.'dashboard/custom_dashboard'.EXT)) {
                $view = 'dashboard/custom_dashboard';
            } else {
                $view = 'dashboard/dashboard';
            }
        }

        if (! file_exists(VIEW_PATH.$view.EXT)) {
            $data = array('status_code' => 404);
            $view = 'error/client_error';
        }

//        $obj = new View();
//        $obj->view($view, $data);

        $view = $this->view('show/dashboard');
        $view->widget = new Widgets();
        $view->conf_dashboard_layout = conf('dashboard_layout', Array());
        //        $view->login = $login;
//        $view->url = url("auth/login/$return");
//        $view->https_disabled = empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off";
//        $view->secure_url = secure_url();

        echo $view->render();
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

        $data = array(
            'widget' => new Widgets(),
        );

        if ($report = $this->modules->getReport($module, $name)) {
            $data['page'] = 'clients';
            $viewpath = $report->view_path;
            $view = $report->view;
        } else {
            $data = array('status_code' => 404);
            $view = 'error/client_error';
            $viewpath = conf('view_path');
        }

        $obj = new View();
        $obj->view($view, $data, $viewpath);
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
            $viewpath = VIEW_PATH;
        }

        $obj = new View();
        $obj->view($view, $data, $viewpath);
    }
}
