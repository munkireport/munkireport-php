<?php

namespace munkireport\lib;

use Symfony\Component\Yaml\Yaml;
use Illuminate\Filesystem\Filesystem;
use \View;

class Dashboard
{
    private $config, $dashboards = [], $loaded = false, $filesystem;
    
    public function __construct($config)
    {
        $this->config = $config;
        $this->dashboards['default'] = [
          'name' => 'default',
          'dashboard_layout' => $config['default_layout'],
          'display_name' => 'Dashboard',
          'hotkey' => 'd',
        ];
        $this->fileSystem = new Filesystem;
        $this->loadAll();
    }

    private function dashboardExists($dashboard)
    {
        return isset($this->dashboards[$dashboard]);
    }
    
    private function getDashboardLayout($dashboard)
    {
        return $this->dashboards[$dashboard]['dashboard_layout'];
    }

    private function addDashboard($path)
    {
        try {
            $filename = $this->fileSystem->name($path);
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
            $this->dashboards[$filename] = [
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
              foreach($this->fileSystem->glob($dir . '/*.yml') AS $file)
              {
                  $this->addDashboard($file);
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
          $data['dashboard_layout'] = $this->getDashboardLayout($dashboard);
      }
      else
      {
          $data = ['status_code' => 404];
          $view = 'error/client_error';
      }
      $data['widget'] = new Widgets(conf('widget'));
      $obj = new View();
      $obj->view($view, $data);

    }
}
