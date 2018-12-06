<?php

namespace munkireport\lib;

use Symfony\Component\Yaml\Yaml;
use \View;

class Dashboard
{
    private $config;
    
    public function __construct($config)
    {
        $this->config = $config;
    }
    
    private function fullPath($dir, $file)
    {
        return rtrim($dir, '/') . '/' . $file;
    }
    
    public function dashboardExists($dashboard)
    {
        foreach($this->config['search_paths'] as $dir)
        {
            foreach(scandir($dir) AS $filename)
            {
                if($filename == $dashboard . '.yml')
                {
                    return true;
                }
            }
        }
        return false;
    }
    
    public function getDashboard($dashboard)
    {
        foreach($this->config['search_paths'] as $dir)
        {
            if(is_file($this->fullPath($dir, $dashboard . '.yml')))
            {
                return $this->fullPath($dir, $dashboard . '.yml');
            }
        }
        return false;
    }

    
    public function render($dashboard)
    {
      $view = $this->config['template'];

      if($this->dashboardExists($dashboard))
      {
          try {
              $data['dashboard_layout'] = Yaml::parseFile($this->getDashboard($dashboard));
          } catch (\Exception $e) {
             // Do something
          }
      }
      elseif ( $dashboard == 'default')
      {
          $data['dashboard_layout'] = $this->config['default_layout'];
      }
      else
      {
          $data = ['status_code' => 404];
          $view = 'error/client_error';
      }
      $data['widget'] = new Widgets();

      $obj = new View();
      $obj->view($view, $data);

    }

    

}
