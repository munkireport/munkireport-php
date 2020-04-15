<?php

namespace munkireport\lib;

use Symfony\Component\Yaml\Yaml;
use \View;

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
        view($listingData->view, $data, $listingData->view_path);
    }

    private function _renderYAML($listingData, $data)
    {
        $data = $data + Yaml::parseFile($this->_getPath($listingData, 'yml'));
        view($this->template, $data);
    }

    private function _renderPageNotFound()
    {
        $data = ['status_code' => 404];
        $view = 'error/client_error';
        view($view, $data);
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
