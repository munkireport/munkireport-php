<?php
class show extends Controller
{
    private $modules;
    public function __construct()
    {
        if (! $this->authorized()) {
            redirect('auth/login');
        }

        $this->modules = $modules = getMrModuleObj();
    }

    public function index()
    {
        redirect('show/dashboard');
    }

    public function dashboard($which = '')
    {
        include_once(APP_PATH . '/lib/munkireport/Widgets.php');

        $data['widget'] = new munkireport\Widgets();

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

        $obj = new View();
        $obj->view($view, $data);
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

    public function reports($which = 'default')
    {
        include_once(APP_PATH . '/lib/munkireport/Widgets.php');

        $data = array(
            'widget' => new munkireport\Widgets(),
        );

        if ($which) {
            $data['page'] = 'clients';
            $view = 'report/'.$which;
        } else {
            $data = array('status_code' => 404);
            $view = 'error/client_error';
        }

        $obj = new View();
        $obj->view($view, $data);
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
