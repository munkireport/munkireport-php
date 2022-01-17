<?php

namespace munkireport\lib;

use Symfony\Component\Yaml\Yaml;
use \View;

/**
 * The listing class renders a "named" listing view template (either .php or .yml) which can be in the core views path
 * or as part of a module.
 */
class Listing
{
    private $listingData, $template;
    
    public function __construct($listingData)
    {
        $this->listingData = $listingData;
        $this->template = 'listings/default';
        return $this;
    }

    public function render($data = [])
    {
        if( ! $this->listingData){
            $this->_renderPageNotFound();
        }

        $data = [
            'page' => 'clients',
            'scripts' => ["clients/client_list.js"],
        ] + $data;

        if( $this->_getType($this->listingData) == 'yaml'){
            $this->_renderYAML($this->listingData, $data);
        }else{
            $this->_renderPHP($this->listingData, $data);
        }
    }

    private function _renderPHP($listingData, $data)
    {    
        mr_view($listingData->view, $data, $listingData->view_path);
    }

    private function _renderYAML($listingData, $data)
    {
        $data = $data + Yaml::parseFile($this->_getPath($listingData, 'yml'));
        mr_view($this->template, $data);
    }

    private function _renderPageNotFound()
    {
        $data = ['status_code' => 404];
        $view = 'error/client_error';
        mr_view($view, $data);
        exit;
    }

    private function _getType($pathComponents) 
    {
        return is_readable( $this->_getPath($pathComponents, 'yml')) ? 'yaml' : 'php';
    }

    private function _getPath($pathComponents, $extension)
    {
        return $pathComponents->view_path . $pathComponents->view . '.' . $extension;
    }
}
