<?php

namespace munkireport\lib;

use Philo\Blade;



/**
* Widgets class
*
* Retrieves widgets from custom folder, module folder and widget folder
*
*/
class Widgets
{

    private $widgetList = array();
    private $widgetNotFound;

    public function __construct()
    {
        // Not found widget
        $this->widgetNotFound = (object) array(
            'file' => 'unknown_widget',
            'vars' => '',
            'path' => conf('view_path') . 'widgets/',
        );

        $moduleManager = getMrModuleObj();

        foreach( $moduleManager->getInfo('widgets') as $module => $widgets){
            foreach($widgets as $id => $info){

                    // Found a widget, add it to widgetList
                    $this->widgetList[$id] = (object) array(
                        'file' => $info['view'],
                        'vars' => '',
                        'path' => $moduleManager->getPath($module, '/views/'),
                    );
            }
        }

        // Get widgets in widget directory
        $this->searchWidgetsInFolder(conf('view_path') . 'widgets/');

        // Get widgets in custom modules
        if(conf('custom_folder')){

            $customPath = conf('custom_folder');

            if (is_dir($customPath.'views/widgets/'))
            {
                $this->searchWidgetsInFolder($customPath.'views/widgets/');
            }
        }
    }

    public function get($widgetName)
    {
        if(isset($this->widgetList[$widgetName]))
        {
            return $this->widgetList[$widgetName];
        }

        $this->widgetNotFound->vars = array('widgetName' => $widgetName);

        return $this->widgetNotFound;
    }

    public function view($viewObj, $widgetName)
    {
        $widget = $this->get($widgetName);
        $viewObj->view($widget->file, $widget->vars, $widget->path);
    }

    public function render($widgetName)
    {
        $cache_path = APP_ROOT . '/storage/framework/views';

        if (isset($this->widgetList[$widgetName])) {
            $widget = $this->widgetList[$widgetName];
            $blade = new Blade($widget->path, $cache_path);
            $vo = $blade->view()->make($widget->file);
            echo $vo->render();
        }
    }


    private function searchWidgetsInFolder($viewpath)
    {
        foreach (scandir($viewpath) as $view)
        {
            if(preg_match('/(.*)_widget.php/', $view, $matches))
            {
                // Found a widget, add it to widgetList
                $this->widgetList[$matches[1]] = (object) array(
                    'file' => $matches[1].'_widget',
                    'vars' => '',
                    'path' => $viewpath,
                );
            }
        }
    }
}
