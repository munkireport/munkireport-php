<?php
class show extends Controller
{
    private $data;

    public function __construct()
    {
        if (! $this->authorized()) {
            redirect('auth/login');
        }
        include_once(APP_PATH . '/lib/munkireport/Listings.php');
        $this->data = array(
            'session' => $_SESSION,
            'listing' => new munkireport\Listings(),
        );
    }

    public function index()
    {
        redirect('show/dashboard');
    }

    public function dashboard($which = '')
    {
        include_once(APP_PATH . '/lib/munkireport/Widgets.php');

        $this->data['widget'] = new munkireport\Widgets();

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
            $this->data = array('status_code' => 404);
            $view = 'error/client_error';
        }

        $obj = new View();
        $obj->view($view, $this->data);
    }

    public function listing($module = '', $name = '')
    {
        if ($module && $name) {
            $this->data['page'] = 'clients';
            $this->data['scripts'] = array("clients/client_list.js");
            $viewpath = conf('module_path') . $module . '/views/';
            $view = $name.'_listing';
        } else {
            $this->data = array('status_code' => 404);
            $view = 'error/client_error';
            $viewpath = conf('view_path');
        }

        $obj = new View();
        $obj->view($view, $this->data, $viewpath);
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
