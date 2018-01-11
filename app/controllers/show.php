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
        if ($which) {
            if (! file_exists(VIEW_PATH.$view.EXT)) {
                $view = $this->view('error/client_error');
                $view->status_code = 404;
                echo $view->render();
                return;
            }

            $view = $this->view('dashboard/'.$which);

        } else if (file_exists(VIEW_PATH.'dashboard/custom_dashboard'.EXT)) {
            $view = $this->view('show/custom_dashboard');
        } else {
            $view = $this->view('show/dashboard');
        }

        $view->widget = new Widgets();
        $view->conf_dashboard_layout = conf('dashboard_layout', Array());

        echo $view->render();
    }

    public function listing($module = '', $name = '')
    {
        $listing = $this->modules->getListing($module, $name);

        if (!$listing) {
            $view = $this->view('error/client_error');
            $view->status_code = 404;
            echo $view->render();
            return;
        }

        $view = $this->view($listing->view, Array($listing->view_path));
        $view->page = 'clients';
        $view->scripts = Array("clients/client_list.js");
        
        echo $view->render();
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
