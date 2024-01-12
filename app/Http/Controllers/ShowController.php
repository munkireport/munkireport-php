<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use munkireport\lib\Dashboard;
use munkireport\lib\Listing;
use munkireport\lib\Widgets;

/**
 * Class ShowController
 *
 * Replaces MunkiReport Show.php Controller.
 *
 * @package App\Http\Controllers
 * @see \munkireport\controller\Show
 */
class ShowController extends Controller
{
    private $modules;
    public function __construct()
    {
        if (!Str::contains(config('auth.methods'), 'NOAUTH')) {
            $this->middleware('auth');
        }

        $this->modules = $modules = getMrModuleObj()->loadInfo();
    }

    public function index()
    {
        return redirect('/show/dashboard/default');
    }

    public function dashboard($which = '')
    {
        if($which == '')
        {
            return redirect('/show/dashboard/default');
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
                'template' => env('DASHBOARD_TEMPLATE', 'dashboard/dashboard'),
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
