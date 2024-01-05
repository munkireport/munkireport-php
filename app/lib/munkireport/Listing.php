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
    private object $listingData;

    /**
     * @var string The template to render (usually for .yaml listings only).
     */
    private string $template;

    /**
     * @param object $listingData A stdClass type object with properties for 'module', 'view', and 'view_path'
     */
    public function __construct(object $listingData)
    {
        $this->listingData = $listingData;
        $this->template = 'listings/default';
    }

    public function render($data = [])
    {
        if (!$this->listingData) {
            $this->_renderPageNotFound();
        }

        $data = [
                'page' => 'clients',
                'scripts' => ["clients/client_list.js"],
            ] + $data;

        if ($this->_getType($this->listingData) == 'yaml') {
            return $this->_renderYAML($this->listingData, $data);
        } else {
            return $this->_renderPHP($this->listingData, $data);
        }
    }

    /**
     * Render a listing which uses a plain PHP template by capturing the KISSMVC view's output buffer
     * and returning it.
     *
     * @param object $listingData Information about the path and view name to render.
     * @param array $data Data to be passed to the view template.
     * @return string
     */
    private function _renderPHP(object $listingData, array $data): string
    {
        return mr_view_output($listingData->view, $data, $listingData->view_path);
    }

    private function _renderYAML($listingData, $data)
    {
        $data = $data + Yaml::parseFile($this->_getPath($listingData, 'yml'));
        return view($this->template, $data);
    }

    private function _renderPageNotFound()
    {
        $data = ['status_code' => 404];
        $view = 'error/client_error';
        mr_view($view, $data);
        exit;
    }

    private function _getType($pathComponents): string
    {
        return is_readable($this->_getPath($pathComponents, 'yml')) ? 'yaml' : 'php';
    }

    private function _getPath($pathComponents, $extension): string
    {
        return $pathComponents->view_path . $pathComponents->view . '.' . $extension;
    }
}
