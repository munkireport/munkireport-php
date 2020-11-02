<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use munkireport\lib\Dashboard;
use munkireport\lib\Database;

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
    public function __construct()
    {
        if (!Str::contains(config('auth.methods'), 'NOAUTH')) {
            $this->middleware('auth');
//            Gate::authorize('global');
        }
    }

    //===============================================================

    /**
     * DataBase
     *
     * Get Database info and status
     *
     */
    public function DataBaseInfo()
    {
        $out = array(
            'db.driver' => '',
            'db.connectable' => false,
            'db.writable' => false,
            'db.size' => '',
            'error' => '',
            'version' => ''
        );

        $db = new Database(conf('connection'));
        //echo '<pre>'; var_dump($db);
        if ($db->connect()) {
            $out['db.connectable'] = true;
            $out['db.driver'] = $db->get_driver();

            if ($db->isWritable()) {
                $out['db.writable'] = true;
            } else {
                $out['error'] = $db->getError();
            }
            $out['db.size'] = $db->size();
            $out['version'] = $db->get_version();

        } else {
            $out['error'] = $db->getError();
        }
        //echo '<pre>'; var_dump($db);
        // Get engine
        // Get permissions
        // Do a write
        // Do a read
        // Get tables
        // Get size
        return response()->json($out);
    }

    /**
     * php information
     *
     * Retrieve information about php
     *
     */
    public function phpInfo()
    {
        ob_start();
        phpinfo(11);
        $raw = ob_get_clean();
        $phpinfo = array('phpinfo' => array());

        // Remove credits
        $nocreds = preg_replace('#<h1>PHP Credits.*#s', '', $raw);
        if (preg_match_all('#(?:<h2>(?:<a name=".*?">)?(.*?)(?:</a>)?</h2>)|(?:<tr(?: class=".*?")?><t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>)?)?</tr>)#s', $nocreds, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                if (strlen($match[1])) {
                    $phpinfo[$match[1]] = array();
                } elseif (isset($match[3])) {
                    $keys1 = array_keys($phpinfo);
                    $phpinfo[end($keys1)][$match[2]] = isset($match[4]) ? $match[3] . ' ('.$match[4].')' : str_replace(',', ', ', $match[3]);
                } else {
                    $keys1 = array_keys($phpinfo);
                    $phpinfo[end($keys1)][] = trim(strip_tags($match[2]));
                }
            }
        }
        //echo '<pre>';print_r($phpinfo);return;

        return response()->json($phpinfo);
    }

    /**
     * Display the Widget Gallery
     */
    public function widgets()
    {
        $moduleManager = getMrModuleObj();
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
        $data['page'] = 'clients';
        $data['scripts'] = array("clients/client_list.js");
        return view('system.status', $data);
    }

    /**
     * Display database tools
     */
    public function database()
    {
        $data['page'] = 'clients';
        $data['scripts'] = array("clients/client_list.js");
        $data['stylesheets'] = array('system/database.css');
        return view('system.database', $data);
    }
}
