<?php

namespace munkireport\controller;

use \Controller, \View;

class Install extends Controller
{
    private $moduleManager;

    public function __construct()
    {
        $this->moduleManager = getMrModuleObj();
    }

    /**
     * Install all modules
     *
     * @author Bochoven, A.E. van
     **/
    public function index()
    {
        $this->modules();
    }

    /**
     * Show all available modules
     *
     * @param optional string format the output
     * @author
     **/
    public function dump_modules($format = '')
    {
        $modules = array_keys($this->moduleManager->getModuleList());

        switch ($format) {
            case 'config':
                $str = implode("','", $modules);
                echo "\$conf['modules'] = array('$str');\n";
                break;
            case 'json':
                break;
            case 'autopkg':
                $obj = new View();
                $obj->view('install/modules_autopkg', array('modules' => $modules));
                break;
            default:
                echo implode("\n", $modules);
        }
    }

    /**
     * Install only selected modules
     *
     * @author Bochoven, A.E. van
     **/
    public function modules()
    {

        $data['install_scripts'] = array();
        $data['uninstall_scripts'] = array();

        // Get required modules from config
        $use_modules = conf('modules', array());

        // Override with requested modules
        if (func_get_args()) {
            $use_modules = func_get_args();
        }

        // Collect install scripts from modules
        foreach ($this->moduleManager->getModuleList() as $module => $modulePath) {

            // Check if we need to uninstall this module
            if (! in_array($module, $use_modules)) {
            // Check if there is an uninstall script
                if (is_file($modulePath.'/scripts/uninstall.sh')) {
                    $data['uninstall_scripts'][$module] = $modulePath.'/scripts/uninstall.sh';
                }

                continue;
            }

            // Check if there is a install script
            if (is_file($modulePath.'/scripts/install.sh')) {
                $data['install_scripts'][$module] = $modulePath.'/scripts/install.sh';
            }
        }

        // Buffer script
        ob_start();
        $obj = new View();
        $obj->view('install/install_script', $data);
        $content = ob_get_clean();

        // Get etag header
        $etagHeader = ( isset($_SERVER["HTTP_IF_NONE_MATCH"]) ? trim($_SERVER["HTTP_IF_NONE_MATCH"]) : false );

        // generate the etag from content
        $etag = md5($content);

        //set etag-header
        header("Etag: ".$etag);

        // if last modified date is same as "HTTP_IF_MODIFIED_SINCE", send 304 then exit
        if ($etag === $etagHeader) {
            header("HTTP/1.1 304 Not Modified");
            exit;
        }

        // new content modified, so output content
        echo $content;
        exit;
    }

    public function plist()
    {
        $obj = new View();
        $obj->view('install/install_plist');
    }
}
