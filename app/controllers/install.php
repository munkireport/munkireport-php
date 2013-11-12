<?php
class install extends Controller
{
    function __construct()
    {
    }

    function index()
    {
        $data['install_scripts'] = array();
        $data['uninstall_scripts'] = array();

        // Get required modules from config
        $use_modules = conf('modules');

        // Collect install scripts from modules
        foreach(scandir(conf('module_path')) AS $module)
        {
            // Skip everything that starts with a dot
            if(strpos($module, '.') === 0)
            {
                continue;
            }

            // Check if we need to uninstall this module
            if( $use_modules && ! in_array($module, $use_modules))
            {
                // Check if there is an uninstall script
                if(is_file(conf('module_path').$module.'/scripts/uninstall.sh'))
                {
                    $data['uninstall_scripts'][$module] = conf('module_path').$module.'/scripts/uninstall.sh';
                }

                continue;
            }

            // Check if there is a install script
            if(is_file(conf('module_path').$module.'/scripts/install.sh'))
            {
                $data['install_scripts'][$module] = conf('module_path').$module.'/scripts/install.sh';
            }
        }

    	$obj = new View();
        $obj->view('install/install_script', $data);
    }




	function plist()
	{
		$obj = new View();
		$obj->view('install/install_plist');
	}
}
