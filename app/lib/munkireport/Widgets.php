<?php

namespace munkireport\lib;
use Illuminate\Filesystem\Filesystem;

/**
* Widgets class
*
* Retrieves widgets from custom folder, module folder and widget folder
*
*/
class Widgets
{

    private $conf, $widgetList = [], $fileSystem;

    public function __construct($conf)
    {
        $this->conf = $conf;

        $this->fileSystem = new Filesystem;
        
        $this->addUnknownWidget();
        
        $this->addModuleWidgets();

        $this->addLocalWidgets($this->conf['search_paths']);
        // echo '<pre>';print_r($this->widgetList);
    }
    
    private function addUnknownWidget()
    {
        $this->addWidget('unknown_widget', conf('view_path') . 'widgets/unknown_widget.php');
    }
    
    private function addModuleWidgets()
    {
      $moduleManager = getMrModuleObj();

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
        $viewObj->view($widget->file, $widget->vars, $widget->path);
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
