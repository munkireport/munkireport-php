<?php
namespace munkireport\controller;

use \Controller, \View;
use munkireport\lib\Database;
use munkireport\lib\Widgets;
use munkireport\lib\Modules;

class system extends Controller
{
    public function __construct()
    {
        if (! $this->authorized()) {
            die('Authenticate first.'); // Todo: return json?
        }

        if (! $this->authorized('global')) {
            die('You need to be admin');
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
        $obj = new View();
        $obj->view('json', array('msg' => $out));
    }

    //===============================================================

    /**
     * Authentication and Authorization
     *
     * Get Authentication and Authorization data
     *
     */
    public function AuthenticationAndAuthorization()
    {
        # code...
    }
    //===============================================================

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
        $obj = new View();
        $obj->view('json', array('msg' => $phpinfo));
    }
    //===============================================================
    //===============================================================
    //===============================================================

    //===============================================================

    /**
     * undocumented function
     *
     * @return void
     * @author
     **/
    public function show($which = '')
    {
        switch ($which) {
            case 'status':
                $data['page'] = 'clients';
                $data['scripts'] = array("clients/client_list.js");
                $view = 'system/status';
                break;
            case 'widget_gallery':
                $moduleManager = new Modules;
                $data['widgetList'] = $moduleManager->loadInfo(true)->getWidgets();
                $view = 'system/widget_gallery';
                break;
            case 'database':
                $data['page'] = 'clients';
                $data['scripts'] = array("clients/client_list.js");
                $data['stylesheets'] = array('system/database.css');
                $view = 'system/database';
                break;
            default:
                $data = array('status_code' => 404);
                $view = 'error/client_error';
        }

        $obj = new View();
        $obj->view($view, $data);
    }
}
