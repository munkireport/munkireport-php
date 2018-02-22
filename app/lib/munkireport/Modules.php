<?php

namespace munkireport\lib;

/**
* Modules class
*
* Retrieves info about installed modules
*
*/
class Modules
{

    private $moduleList = array();
    private $moduleSearchPaths = array();
    private $allowedModules = array(
      'machine',
      'reportdata',
      'tag',
      'event',
      'comment',
    );
    private $skipInactiveModules = False;

    public function __construct()
    {
        // Populate allowedModules if hide_inactive_modules is true
        if(conf('hide_inactive_modules')){
            $this->skipInactiveModules = True;
        }

        // Custom modules go first
        if(conf('custom_folder')){
            $this->moduleSearchPaths['custom'] = conf('custom_folder').'modules/';
        }

        // And then built-in modules
        $this->moduleSearchPaths['builtin'] = conf('module_path');


    }

    /**
     * Retrieve moduleModelPath
     *
     *
     * @param type var Description
     * @return return type
     */
    public function getModuleModelPath($moduleName, &$modelPath)
    {
        foreach ($this->moduleSearchPaths as $type => $path) {
            if ( file_exists($path . $moduleName . '/' . $moduleName . '_model.php')) {
                $modelPath = $path . $moduleName . '/' . $moduleName . '_model.php';
                return True;
            }
        }
        return False;
    }

    /**
     * Retrieve moduleProcessorPath
     *
     *
     * @param type var Description
     * @return return type
     */
    public function getModuleProcessorPath($moduleName, &$modelPath)
    {
        foreach ($this->moduleSearchPaths as $type => $path) {
            if ( file_exists($path . $moduleName . '/' . $moduleName . '_processor.php')) {
                $modelPath = $path . $moduleName . '/' . $moduleName . '_processor.php';
                return True;
            }
        }
        return False;
    }

    /**
     * Retrieve moduleControllerPath
     *
     *
     * @param type var Description
     * @return return type
     */
    public function getmoduleControllerPath($moduleName, &$controllerPath)
    {
        foreach ($this->moduleSearchPaths as $type => $path) {
            if ( file_exists($path . $moduleName . '/' . $moduleName . '_controller.php')) {
                $controllerPath = $path . $moduleName . '/' . $moduleName . '_controller.php';
                return True;
            }
        }
        return False;
    }

    /**
     * Retrieve moduleMigrationPath
     *
     *
     * @param type var Description
     * @return boolean
     */
    public function getModuleMigrationPath($moduleName, &$migrationPath)
    {
        foreach ($this->moduleSearchPaths as $type => $path) {
            if (is_dir($path . $moduleName . '/migrations')) {
                $migrationPath = $path . $moduleName . '/migrations';
                return True;
            }
        }
        return False;
    }

    /**
     * Retrieve list of all available modules
     *
     */
    public function getModuleList()
    {
        $modules = array();
        foreach ($this->moduleSearchPaths as $path)
        {
            foreach (scandir($path) as $module)
            {
                if (is_file($path.$module.'/scripts/install.sh')) {
                    // Don't overwrite custom modules
                    if( ! isset($modules[$module]))
                    {
                        $modules[$module] = $path.$module;
                    }
                }
            }
        }
        return $modules;
    }

    /**
     * Load Module info
     *
     * Load info from provides.php
     *
     * @param boolean $allModules If true, don't mind $skipInactiveModules
     * @return none
     */
    public function loadInfo($allModules = False)
    {
        if($allModules){
            $skipInactiveModules = False;
        }else{
            $skipInactiveModules = $this->skipInactiveModules;
        }

        if($skipInactiveModules){
            $allowedModules = array_merge($this->allowedModules, conf('modules', array()));
        }else{
            $allowedModules = array(); // No need to set this.
        }

        $this->collectModuleInfo($this->moduleSearchPaths, $skipInactiveModules, $allowedModules);

        return $this;

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

    /**
     * Get listing info based on module and name
     *
     * Undocumented function long description
     *
     * @param string $module Name of module
     * @param string $name Name of listing
     * @return Object of false
     */
    public function getListing($module, $name)
    {
        if(isset($this->moduleList[$module]['listings'])){
            if( isset($this->moduleList[$module]['listings'][$name]['view'])) {
                return (object) array(
                    'view_path' => $this->getPath($module, '/views/'),
                    'view' => $this->moduleList[$module]['listings'][$name]['view'],
                );
            }
        }
        return False;
    }

    public function getReport($module, $name)
    {
        if(isset($this->moduleList[$module]['reports'][$name])){
            return (object) array(
                'view_path' => $this->getPath($module, '/views/'),
                'view' => $this->moduleList[$module]['reports'][$name]['view'],
            );
        }
        return False;
    }

    public function getPath($module, $append = '')
    {
        if( isset( $this->moduleList[$module]['path'])){
            return $this->moduleList[$module]['path'] . $append;
        }
        return False;
    }

    // Add client tabs info
    public function addTabs(&$tabArray)
    {
        foreach( $this->getInfo('client_tabs') as $module => $client_tabs){
            foreach($client_tabs as $id => $info){
                $info['view_path'] = $this->getPath($module, '/views/');
                $tabArray[$id] = $info;
            }
        }
    }

    /**
     * Get data to create dropdown nav
     *
     * @param string $kind 'reports' or 'listings'
     * @param string $baseUrl 'show/report' or 'show/listing'
     * @param $page current page url path
     * @return array
     */
    public function getDropdownData($kind, $baseUrl, $page)
    {
        $out = array();
        foreach( $this->getInfo($kind) as $module => $kindArray){
            foreach($kindArray as $itemName => $itemData){
                $i18n = isset($itemData['i18n']) ? $itemData['i18n'] : 'nav.' . $kind . '.' . $itemName;
                $out[] = (object) array(
                  'url' => url($baseUrl.'/'.$module.'/'.$itemName),
                  'name' => $itemName,
                  'class' => $page == $baseUrl.'/'.$module.'/'.$itemName ? 'active' : '',
                  'i18n' => $i18n,
                );
            }
        }

        return $out;
    }


    //  Get listings info
    public function getListingDropdownData($page)
    {
        $out = array();
        foreach( $this->getInfo('listings') as $module => $listings){
            foreach($listings as $listingInfo){
                $name = str_replace('_listing', '', $listingInfo['view']);
                $i18n = isset($listingInfo['i18n']) ? $listingInfo['i18n'] : 'nav.listings.' . $name;
                $out[] = (object) array(
                  'url' => url('show/listing/'.$module.'/'.$name),
                  'name' => $name,
                  'class' => substr_compare( $page, $name, -strlen( $name ) ) === 0 ? 'active' : '',
                  'i18n' => $i18n,
                );
            }
        }

        return $out;
    }

    public function getModuleLocales($lang='en')
    {
        $localeList = array();
        foreach( $this->moduleList as $module => $info){
            $localePath = sprintf('%s/locales/%s.json', $info['path'], $lang);
            if(is_file($localePath)){
                $localeList[] = sprintf('"%s": %s', $module, file_get_contents($localePath));
            }
        }
        return '{'.implode(",\n", $localeList).'}';
    }


    private function collectModuleInfo($modulePaths, $skipInactiveModules = False, $allowedModules)
    {
        foreach ($modulePaths as $basePath)
        {
            if( ! is_dir($basePath)){
                // Emit warning?
                continue;
            }
            foreach (scandir($basePath) as $module) {

                // Skip inactive modules
                if( $skipInactiveModules && ! in_array($module, $allowedModules)){
                    continue;
                }

                // Find provides.php
                if (is_file($basePath.$module.'/provides.php') && ! isset($this->moduleList[$module]))
                {
                    $this->moduleList[$module] = require $basePath.$module.'/provides.php';
                    $this->moduleList[$module]['path'] = $basePath.$module.'/';
                }
            }
        }
    }
}
