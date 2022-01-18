<?php

namespace munkireport\lib;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

/**
* Widgets class
*
* Retrieves widgets from custom folder, module folder and widget folder.
* Acts similarly to dashboard service, maintains a registry of discovered items.
*
*/
class Widgets
{
    /**
     * @var array Widget configuration, usually from config/widget.php
     */
    private $conf;

    /**
     * @var array A list of discovered widgets as an associative array of name => detail
     */
    private $widgetList = [];

    /**
     * @var Filesystem The filesystem object used to scan dashboard search_paths
     */
    private $fileSystem;

    public function __construct($conf)
    {
        $this->conf = $conf;

        $this->fileSystem = new Filesystem;

        $this->addModuleWidgets();

        foreach($this->conf['core'] as $name => $path) {
            $this->addWidget($name, $path);
        }

        $this->addLocalWidgets($this->conf['search_paths']);
        // echo '<pre>';print_r($this->widgetList);

    }

    private function addModuleWidgets()
    {
      $moduleManager = app(Modules::class);

      foreach( $moduleManager->getInfo('widgets') as $module => $widgets){
          foreach($widgets as $id => $info){
            $this->addWidget($id, $moduleManager->getPath($module, '/views/' . $info['view']));
          }
      }
    }

    private function addLocalWidgets($search_paths)
    {
        foreach($search_paths as $path)
        {
            $path = rtrim($path, '/') . '/';
            if (is_dir($path))
            {
                $this->searchWidgetsInFolder($path);
            }
        }
    }

    public function get($widgetName, $data = [])
    {
        if (isset($data['widget'])) {
            $widgetName = $data['widget'];
        }

        if(isset($this->widgetList[$widgetName]))
        {
            $widget = $this->widgetList[$widgetName];
            $widget->vars = $data;
        }
        else
        {
          $widget = $this->widgetList['unknown_widget'];
          $widget->vars = ['widgetName' => $widgetName];
        }

        return $widget;
    }

    public function view($viewObj, $widgetName, $data = [])
    {
        $widget = $this->get($widgetName, $data);
        if ($this->getType($widget->path, $widget->file) == 'yaml') {
            try {
                $data = array_merge($data ?? [], Yaml::parseFile($widget->path . $widget->file . '.yml'));
            } catch (\Throwable $th) {
                $data = [
                    'type' => 'error',
                    'title' => 'YAML error',
                    'msg' => $th->getMessage()
                ];
            }
            $viewObj->viewWidget($data);

        }else{
            $viewObj->view($widget->file, $widget->vars, $widget->path);
        }
    }

    /**
     * Get the name of a Laravel Blade View Component that matches the name of the widget.
     *
     * If a newer style component can be found, it will be returned, otherwise you will get the x-widget.legacy component
     * which just wraps over the Widget->view().
     *
     * @param string $widgetName
     * @param array|null $data Data to merge that will be passed to the widget view.
     * @return array ['component name', $data]
     */
    public function getComponent(string $widgetName, ?array $data = null): array
    {
        $widget = $this->get($widgetName);
        if ($this->getType($widget->path, $widget->file) == 'yaml') {
            try {
                $data = array_merge($data ?? [], Yaml::parseFile($widget->path . $widget->file . '.yml'));

                switch ($data['type']) {
                    case 'bargraph':
                        return ['widget.bargraph', $data];
                    case 'button':
                        return ['widget.button', $data];
                    case 'error':
                        return ['widget.error', $data];
                    case 'scrollbox':
                        return ['widget.scrollbox', $data];
                    case 'spacer':
                        return ['widget.spacer', $data];
                    case 'unknown':
                    default:
                        return ['widget.unknown', $data];
                }
            } catch (\Throwable $th) {
                $data = [
                    'type' => 'error',
                    'title' => 'YAML error',
                    'msg' => $th->getMessage()
                ];

                return ['widget.unknown', $data];
            }
        } else {
            return ['widget.legacy', $data];
        }
    }

    private function getType($path, $view) 
    {
        return is_readable( $path . $view . '.yml') ? 'yaml' : 'php';
    }

    private function fileNameToName($file)
    {
        return str_replace('_widget', '', $this->fileSystem->name($file));
    }

    private function searchWidgetsInFolder($viewpath)
    {
        foreach($this->fileSystem->glob($viewpath . '*_widget.php') AS $file)
        {
            $this->addWidget($this->fileNameToName($file), $file);
        }
    }

    private function addWidget($name, $file)
    {
          $this->widgetList[$name] = (object) [
              'file' => $this->fileSystem->name($file),
              'vars' => '',
              'path' => $this->fileSystem->dirname($file) . '/',
          ];
    }
}
