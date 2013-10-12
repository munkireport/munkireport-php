<?php
class install extends Controller
{
    function __construct()
    {
    }

    function index()
    {
    	$data['scripts'] = array();

        // Collect install scripts from modules
        foreach(scandir(conf('module_path')) AS $module)
        {
            // Skip everything that starts with a dot
            if(strpos($module, '.') === 0)
            {
                continue;
            }

            // Check if there is a install script
            if(is_file(conf('module_path').$module.'/scripts/install.sh'))
            {
                $data['scripts'][$module] = conf('module_path').$module.'/scripts/install.sh';
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
