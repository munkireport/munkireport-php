<?php

namespace App\Http\Controllers;

use App\SystemInformation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use munkireport\lib\Dashboard;
use Compatibility\Kiss\Database;
use munkireport\lib\Modules;
use Munkireport\Osquery\Tables\SystemInfo;

/**
 * Class SystemController
 *
 * The System Controller contains all System Administration related functionality and should
 * only be accessible by users with the `admin` role.
 *
 * @package App\Http\Controllers
 */
class SystemController extends Controller
{
    /**
     * Display the Widget Gallery
     */
    public function widgets()
    {
        $moduleManager = app(Modules::class);
        $layoutList = [];
        foreach($moduleManager->loadInfo(true)->getWidgets() as $widget){
            $widgetName = str_replace('_widget', '', $widget->name);
            $layoutList[$widgetName] = [
                'widget_obj' => $widget,
            ];
        }
        $gallery = [
            'search_paths' => [],
            'template' => 'system/widget_gallery',
            'default_layout' => $layoutList,
        ];
        $db = new Dashboard($gallery, false);
        $db->render('default');
    }

    /**
     * Display system settings and health information.
     */
    public function status()
    {
        Gate::authorize('global');

        $data = [
            'connection' => SystemInformation::getDatabaseInformation(),
            'php' => SystemInformation::getPhpInformationByFunc(),
        ];

        return view('system.status', $data);
    }

    /**
     * Get the content of phpinfo() behind an admin gate
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|null
     */
    public function php_info()
    {
        Gate::authorize('global');

        return view('system.phpinfo');
    }

    /**
     * Display database migration tools
     */
    public function database()
    {
        Gate::authorize('global');

        return view('system.database');
    }
}
