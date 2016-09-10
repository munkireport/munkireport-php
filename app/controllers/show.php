<?php
class show extends Controller
{
    public function __construct()
    {
        if (! $this->authorized()) {
            redirect('auth/login');
        }
    }

    public function index()
    {
        redirect('show/dashboard');
    }

    public function dashboard($which = '')
    {
        $data = array('session' => $_SESSION);

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

    public function listing($which = '')
    {
        if ($which) {
            $data['page'] = 'clients';
            $data['scripts'] = array("clients/client_list.js");
            $view = 'listing/'.$which;
        } else {
            $data = array('status_code' => 404);
            $view = 'error/client_error';
        }

        $obj = new View();
        $obj->view($view, $data);
    }

    public function reports($which = 'default')
    {
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
