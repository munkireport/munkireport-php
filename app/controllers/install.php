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
     * Show all installed modules
     *
     * @param optional string show readme as well
     * @author 
     **/
    function dump_modules($show_info = FALSE)
    {
        foreach($this->_get_modules() as $module)
        {
            printf("%s\n", $module);
            
            if($show_info)
            {
                if(is_file(conf('module_path').$module.'/README.md'))
                {
                    echo file_get_contents(conf('module_path').$module.'/README.md');
                }
            }
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
        foreach(scandir(conf('module_path')) AS $module)
        {
            // Skip everything that starts with a dot
            if(strpos($module, '.') === 0)
            {
                continue;
            }

            $modules[] = $module;
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
        $use_modules = func_get_args() or conf('modules', array());

        // Collect install scripts from modules
        foreach(scandir(conf('module_path')) AS $module)
        {
            // Skip everything that starts with a dot
            if(strpos($module, '.') === 0)
            {
                continue;
            }

            // Check if we need to uninstall this module
            if( ! in_array($module, $use_modules))
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
