<?php

namespace munkireport;

/**
* Modules class
*
* Retrieves info about installed modules
*
*/
class Modules
{

    private $moduleList = array();
    private $allowedModules = 'all';
    private $alwaysShowTheseModules = array(
      'machine',
      'reportdata',
      'tag'
    );

    public function __construct()
    {
        // Populate allowedModules if hide_inactive_modules is true
        if(conf('hide_inactive_modules')){
          $this->allowedModules = conf('modules', array()) + $this->alwaysShowTheseModules;
        }

        // Get Modules in modules
        $this->collectModuleInfo(conf('module_path'));
    }

    // Return info about $about
    public function getInfo($about = '')
    {
        if( ! $about){
            return $this->moduleList;
        }

        $out = array();
        foreach ($this->moduleList as $module => $moduleInfo) {
            if(array_key_exists($about, $moduleInfo)){
                $out[$module] = $moduleInfo[$about];
            }
        }

        return $out;
    }

    // Add client tabs info
    public function addTabs(&$tabArray)
    {
        foreach( $this->getInfo('client_tabs') as $module => $client_tabs){
            foreach($client_tabs as $id => $info){
                $info['view_path'] = MODULE_PATH . $module . '/views/';
                $tabArray[$id] = $info;
            }
        }
    }

    private function collectModuleInfo($basePath)
    {
        foreach (scandir($basePath) as $module) {

            // Skip disallowed modules
            if( is_array($this->allowedModules) &&  ! in_array($module, $this->allowedModules)){
                continue;
            }

            // Find provides.php
            if (is_file($basePath.$module.'/provides.php'))
            {
                $this->moduleList[$module] = require $basePath.$module.'/provides.php';
            }
        }
    }
}
