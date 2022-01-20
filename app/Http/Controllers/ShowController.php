<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use munkireport\lib\Dashboard;
use munkireport\lib\Listing;
use munkireport\lib\Modules;
use munkireport\lib\Widgets;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $moduleManager = app(Modules::class);
        $this->modules = $moduleManager->loadInfo();
    }

    public function index()
    {
        return redirect('/show/dashboard/default');
    }

    /**
     * Show a dashboard.
     *
     * A dashboard can be defined:
     *
     * - In config/dashboard.php (default dashboard)
     * - In the default local search path (local/dashboards/)
     * - In a user-defined location (any *.yml)
     *
     * The dashboard template defaults to dashboard/dashboard unless overridden.
     *
     * @param string $which
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function dashboard($which = '')
    {
        if($which == '')
        {
            return redirect('/show/dashboard/default');
        }
        $db = new Dashboard(conf('dashboard'));
        $db->render($which);
    }

    /**
     * Render a listing, which is basically a definition of columns for a preset datatable which handles all
     * the render/fetch logic for you.
     *
     * @param string $module Name of the module where the listing is defined
     * @param string $name The name of the listing definition (same as the filename)
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View|string|void
     */
    public function listing(string $module = '', string $name = '')
    {
        $listing = new Listing($this->modules->getListing($module, $name));
        return $listing->render();
    }

    /**
     * Render a report, which is like a standardised dashboard provided by a module that focuses on one
     * area of interest.
     *
     * @param string $module Name of the module where the report is defined.
     * @param string $name The name of the report definition
     * @return \never|void
     */
    public function report(string $module = '', string $name = '')
    {
        $report = $this->modules->getReport($module, $name);

        if ( ! $report){
            return abort(404, "No such report found");
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

    // nb - this was only to test layout conversions, will be removed.
    public function kiss_layout()
    {
        mr_view('empty', []);
        exit;
    }
}
