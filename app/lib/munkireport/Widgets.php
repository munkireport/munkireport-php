<?php

namespace munkireport\lib;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

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

        $this->addSpacerWidget();

        $this->addCoreWidgets();

        $this->addModuleWidgets();

        $this->addLocalWidgets($this->conf['search_paths']);
        // echo '<pre>';print_r($this->widgetList);

    }

    private function addUnknownWidget()
    {
        $this->addWidget('unknown_widget', conf('view_path') . 'widgets/unknown_widget.php');
    }

    private function addSpacerWidget()
    {
        $this->addWidget('spacer', conf('view_path') . 'widgets/spacer_widget.php');
    }

    private function addCoreWidgets()
    {
        $this->addWidget('client', conf('view_path') . 'reportdata/client_widget.php');
        $this->addWidget('registered_clients', conf('view_path') . 'reportdata/registered_clients_widget.php');
        $this->addWidget('uptime', conf('view_path') . 'reportdata/uptime_widget.php');

        $this->addWidget('duplicated_computernames', conf('view_path') . 'machine/duplicated_computernames_widget.yml');
        $this->addWidget('hardware_basemodel', conf('view_path') . 'machine/hardware_basemodel_widget.yml');
        $this->addWidget('hardware_model', conf('view_path') . 'machine/hardware_model_widget.yml');
        $this->addWidget('hardware_type', conf('view_path') . 'machine/hardware_type_widget.yml');
        $this->addWidget('installed_memory', conf('view_path') . 'machine/installed_memory_widget.yml');
        $this->addWidget('memory', conf('view_path') . 'machine/memory_widget.yml');
        $this->addWidget('new_clients', conf('view_path') . 'machine/new_clients_widget.yml');
        $this->addWidget('os', conf('view_path') . 'machine/os_widget.yml');
        $this->addWidget('osbuild', conf('view_path') . 'machine/osbuild_widget.yml');

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
