<?php
class install extends Controller
{
    function __construct()
    {
    }
    
    /**
     * Install all modules
     *
     * @author Bochoven, A.E. van
     **/
    function index()
    {
        $this->modules();
    }

    /**
     * Show all available modules
     *
     * @param optional string format the output
     * @author
     **/
    function dump_modules($format = '')
    {
        $modules = $this->_get_modules();

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
     * Get installed modules
     *
     * @author AvB
     **/
    function _get_modules()
    {
        $modules = array();

        // Collect install scripts from modules
        foreach (scandir(conf('module_path')) as $module) {
        // Skip everything that starts with a dot
            if (strpos($module, '.') === 0) {
                continue;
            }
            
            // Only include modules with an install script
            if (is_file(conf('module_path').$module.'/scripts/install.sh')) {
                $modules[] = $module;
            }
        }

        return $modules;
    }

    /**
     * Install only selected modules
     *
     * @author Bochoven, A.E. van
     **/
    function modules()
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
        foreach (scandir(conf('module_path')) as $module) {
        // Skip everything that starts with a dot
            if (strpos($module, '.') === 0) {
                continue;
            }

            // Check if we need to uninstall this module
            if (! in_array($module, $use_modules)) {
            // Check if there is an uninstall script
                if (is_file(conf('module_path').$module.'/scripts/uninstall.sh')) {
                    $data['uninstall_scripts'][$module] = conf('module_path').$module.'/scripts/uninstall.sh';
                }

                continue;
            }

            // Check if there is a install script
            if (is_file(conf('module_path').$module.'/scripts/install.sh')) {
                $data['install_scripts'][$module] = conf('module_path').$module.'/scripts/install.sh';
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

    function plist()
    {
        $obj = new View();
        $obj->view('install/install_plist');
    }
}
