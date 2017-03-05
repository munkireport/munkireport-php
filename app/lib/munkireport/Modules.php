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

        // Get Modules in app/modules
        $this->collectModuleInfo(conf('module_path'));

        // Get Module info in custom folder
        if(conf('custom_folder')){
            $this->collectModuleInfo(conf('custom_folder').'modules/');
        }
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
            foreach ($this->moduleList[$module]['listings'] as $listing) {
                if($listing['view'] = $name . '_listing'){
                    return (object) array(
                        'view_path' => $this->getPath($module, '/views/'),
                        'view' => $name . '_listing',
                    );
                }
            }
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
                $this->moduleList[$module]['path'] = $basePath.$module.'/';
            }
        }
    }
}
