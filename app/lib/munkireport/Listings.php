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

    public function __construct()
    {

        // Populate allowedModules if hide_inactive_modules is true
        if(conf('hide_inactive_modules')){
          $this->allowedModules = conf('modules', array()) + array(
            'machine',
            'reportdata'
          );
        }

        // Get Listings in modules
        $this->searchListingsInModules(conf('module_path'));

        // Get Listings in Listing directory
        $this->searchListingsInFolder(conf('view_path') . 'listing/');

        // Get Listings in custom modules
        if(conf('custom_folder')){

            $customPath = conf('custom_folder');

            $this->searchListingsInModules($customPath);

            if (is_dir($customPath.'views/listing/'))
            {
                $this->searchListingsInFolder($customPath.'views/listing/');
            }
        }
    }

    public function get()
    {

    }

    public function view($viewObj, $listingName)
    {
        $listing = $this->get($listingName);
        $viewObj->view($listing->file, $listing->vars, $listing->path);
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
                $this->searchListingsInFolder($basePath.$module.'/views/');
            }
        }
    }

    private function searchListingsInFolder($viewpath)
    {
        foreach (scandir($viewpath) as $view)
        {
            if(preg_match('/(.*)_listing.php/', $view, $matches))
            {
                // Found a Listing, add it to ListingList
                $this->ListingList[$matches[1]] = (object) array(
                    'file' => $matches[1].'_listing',
                    'path' => $viewpath,
                );
            }
        }
    }
}
