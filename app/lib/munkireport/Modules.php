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
    private $allowedModules = array(
      'machine',
      'reportdata',
      'tag'
    );

    public function __construct()
    {
        // Populate allowedModules if hide_inactive_modules is true
        if(conf('hide_inactive_modules')){
            $skipInactiveModules = True;
            $this->allowedModules = array_merge($this->allowedModules, conf('modules', array()));
        }
        else{
            $skipInactiveModules = False;
        }

        // Get Modules in app/modules
        $this->collectModuleInfo(conf('module_path'), $skipInactiveModules);

        // Get Module info in custom folder
        if(conf('custom_folder')){
            $this->collectModuleInfo(conf('custom_folder').'modules/', $skipInactiveModules);
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
                  'class' => substr_compare( $page, $itemName, -strlen( $itemName ) ) === 0 ? 'active' : '',
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

    private function collectModuleInfo($basePath, $skipInactiveModules = False)
    {
        foreach (scandir($basePath) as $module) {

            // Skip inactive modules
            if( $skipInactiveModules && ! in_array($module, $this->allowedModules)){
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
