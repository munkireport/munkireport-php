<?php

namespace munkireport;

/**
* Listings class
*
* Retrieves Listings from custom folder, module folder and Listing folder
*
*/
class Listings
{

    private $listingList = array();
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

        // Get Listings in modules
        $this->searchListingsInModules(conf('module_path'));

        // Get Listings in custom modules
        if(conf('custom_folder')){

            $customPath = conf('custom_folder');

            $this->searchListingsInModules($customPath);

            if (is_dir($customPath.'views/listing/'))
            {
                $this->searchListingsInFolder($customPath.'views/listing/', 'custom');
            }
        }
    }

    public function get()
    {
        return $this->listingList;
    }

    private function searchListingsInModules($basePath)
    {
        foreach (scandir($basePath) as $module) {
        // Skip everything that starts with a dot
            if (strpos($module, '.') === 0) {
                continue;
            }

            // Skip disallowed modules
            if( is_array($this->allowedModules) &&  ! in_array($module, $this->allowedModules)){
                continue;
            }

            // Find a views folder
            if (is_dir($basePath.$module.'/views/'))
            {
                $this->searchListingsInFolder($basePath.$module.'/views/', $module);
            }
        }
    }

    private function searchListingsInFolder($viewpath, $module)
    {
        foreach (scandir($viewpath) as $view)
        {
            if(preg_match('/(.*)_listing.php/', $view, $matches))
            {
                // Found a Listing, add it to ListingList
                $this->listingList[$matches[1]] = (object) array(
                    'name' => $matches[1],
                    'module' => $module,
                );
            }
        }
    }
}
