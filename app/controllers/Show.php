<?php

namespace munkireport\controller;

use MR\Kiss\Controller;
use MR\Kiss\View;

use munkireport\lib\Widgets;
use munkireport\lib\Dashboard;
use munkireport\lib\Listing;

class Show extends Controller
{
    private $modules;
    public function __construct()
    {
        if (! $this->authorized()) {
            mr_redirect('auth/login');
        }

        // Check for maintenance mode
        if(file_exists(APP_ROOT . 'storage/framework/down')) {
            mr_redirect('error/client_error/503');
        }

        $this->modules = $modules = getMrModuleObj()->loadInfo();
    }

    public function index()
    {
        mr_redirect('show/dashboard/default');
    }

    public function dashboard($which = '')
    {
        if($which == '')
        {
            mr_redirect('show/dashboard/default');
        }
        $db = new Dashboard(conf('dashboard'));
        $db->render($which);
    }

    public function listing($module = '', $name = '')
    {
        $listing = new Listing($this->modules->getListing($module, $name));
        $listing->render();
    }

    public function report($module = '', $name = '')
    {
        $report = $this->modules->getReport($module, $name);

        if ( ! $report){
            $this->_pageNotFound();
        }
        
        if ($report->type == 'php') {
            mr_view(
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
                'template' => mr_env('DASHBOARD_TEMPLATE', 'dashboard/dashboard'),
                'default_layout' => [],           
            ]);
            $db->render($report->view);    
        }       
    }

    public function custom($which = 'default')
    {
        if ( ! $which){
            $this->_pageNotFound();
        }else{
            $this->_render($which, func_get_args(), APP_ROOT . 'custom/views/');
        }
    }

    private function _render($view, $data, $viewpath)
    {
        mr_view($view, $data, $viewpath);
    }

    private function _pageNotFound()
    {
        $data = array('status_code' => 404);
        $view = 'error/client_error';
        $viewpath = conf('view_path');
        mr_view($view, $data, $viewpath);
        exit;
    }
}
