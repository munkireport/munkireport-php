<?php

namespace munkireport\lib;

use Symfony\Component\Yaml\Yaml;
use \View;

class Dashboard
{
    private $config, $dashboards, $loaded = false;
    
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
    
    private function isYaml($file)
    {
        return pathinfo($file, PATHINFO_EXTENSION) == 'yml';
    }
    
    private function getName($file)
    {
        return pathinfo($file, PATHINFO_FILENAME);
    }
    
    private function addDashboard($path, $filename)
    {
        try {
            $display_name = $filename;
            $hotkey = '';
            $data = Yaml::parseFile($path);
            if(isset($data['display_name']))
            {
                $display_name = $data['display_name'];
                unset($data['display_name']);
            }
            if(isset($data['hotkey']))
            {
                $hotkey = $data['hotkey'];
                unset($data['hotkey']);
            }
            $this->dashboards[$path] = [
                'name' => $filename,
                'display_name' => $display_name,
                'hotkey' => $hotkey,
                'dashboard_layout' => $data,
            ];
        } catch (\Exception $e) {
            // Do something
        }
    }

    public function loadAll()
    {
        if(! $this->loaded){
          foreach($this->config['search_paths'] as $dir)
          {
              foreach(scandir($dir) AS $file)
              {
                  if($this->isYaml($file))
                  {
                      $this->addDashboard(
                        $this->fullPath($dir, $file),
                        $this->getName($file)
                      );
                  }
              }
          }
          $this->loaded = true;
        }
        return $this;
    }
    
    public function getCount()
    {
        return count($this->dashboards);
    }
    
    public function getDropdownData($baseUrl, $page)
    {
        $out = [];
        foreach( $this->dashboards as $path => $data){
            $out[] = (object) [
              'url' => url($baseUrl.'/'.$data['name']),
              'name' => $data['name'],
              'display_name' => $data['display_name'],
              'hotkey' => $data['hotkey'],
              'class' => $page == $baseUrl.'/'.$data['name'] ? 'active' : '',
            ];
        }

        return $out;
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
