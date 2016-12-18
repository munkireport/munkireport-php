<?php

namespace munkireport;

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
        
        // Get widgets in custom modules
        if(conf('custom_folder')){
            
            $customPath = conf('custom_folder');
            
            $this->searchWidgetsInModules($customPath);
            
            if (is_dir($customPath.'views/widgets/'))
            {
                $this->searchWidgetsInFolder($customPath.'views/widgets/');
            }
        }
        // Get widgets in modules
        $this->searchWidgetsInModules(conf(module_path));
        
        // Get widgets in widget directory
        $this->searchWidgetsInFolder(conf('view_path') . 'widgets/');
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
    
    private function searchWidgetsInModules($basePath)
    {
        foreach (scandir($basePath) as $module) {
        // Skip everything that starts with a dot
            if (strpos($module, '.') === 0) {
                continue;
            }
            
            // Find a views folder
            if (is_dir($basePath.$module.'/views/'))
            {
                $this->searchWidgetsInFolder($basePath.$module.'/views/');
            }
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
