<?php

namespace munkireport\lib;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Yaml\Yaml;
use Illuminate\Filesystem\Filesystem;

/**
 * The Dashboard class acts as a registry of all available dashboards.
 *
 * Possible sources of dashboards are:
 * - 'default', the built-in dashboard which gets its layout from config files
 * - 'search_paths', paths which will be scanned for *.yml files to load as dashboards.
 *
 * It also generates menu items for all registered dashboards, and it provides a way to
 * override the default template (dashboard/dashboard.php) via `DASHBOARD_TEMPLATE`.
 */
class Dashboard
{
    /**
     * @var array Dashboard configuration, usually from config/dashboard.php
     */
    private $config;

    /**
     * @var array An associative array of dashboards, name => details
     */
    private $dashboards = [];

    /**
     * @var bool Indicates whether dashboards have been loaded (Dashboard listing can be eager or lazy loaded)
     */
    private $loaded = false;

    /**
     * @var Filesystem The filesystem object used to scan dashboard search_paths
     */
    private $fileSystem;

    /**
     * @param array $config Dashboard service configuration, usually from config('dashboard')
     * @param bool $loadAll Pass true to load all dashboards from search paths up front.
     */
    public function __construct(array $config, bool $loadAll = true)
    {
        $this->config = $config;
        $this->dashboards['default'] = [
          'name' => 'default',
          'dashboard_layout' => $config['default_layout'],
          'display_name' => 'Dashboard',
          'hotkey' => 'd',
        ];

        if($loadAll){
            $this->fileSystem = new Filesystem;
            $this->loadAll();    
        }
    }

    /**
     * Add a dashboard at a given path.
     *
     * @param string $path
     */
    private function addDashboard(string $path): void
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
            Log::error("Skipped invalid dashboard at: {$path}");
        }
    }

    /**
     * Load all available dashboards from all dashboard search paths
     * which were defined in config `search_paths` or env `DASHBOARD_SEARCH_PATHS`.
     *
     * @return $this
     */
    public function loadAll(): Dashboard
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

    /**
     * Get the number of dashboards available, including search path discovered dashboards.
     *
     * @return int
     */
    public function getCount(): int
    {
        return count($this->dashboards);
    }

    /**
     * Generate a list of menu item attributes for all dashboards found.
     *
     * @param string $baseUrl The base url to insert before the dashboard name in the link.
     * @param string $page The current URL, used to determine which list item has the active class.
     * @return array A list of menu items representing each dashboard.
     */
    public function getDropdownData(string $baseUrl, string $page): array
    {
        $out = [];
        foreach( $this->dashboards as $path => $data){
            $out[] = (object) [
              'url' => mr_url($baseUrl.'/'.$data['name']),
              'name' => $data['name'],
              'display_name' => $data['display_name'],
              'hotkey' => $data['hotkey'],
              'class' => $page == $baseUrl.'/'.$data['name'] ? 'active' : '',
            ];
        }

        return $out;
    }

    /**
     * Render a dashboard by name (using KISSMVC view object)
     *
     * @param string $dashboard Dashboard name
     */
    public function render(string $dashboard): void
    {
      $view = $this->config['template'];

      if(isset($this->dashboards[$dashboard]))
      {
          $data['dashboard_layout'] = $this->dashboards[$dashboard]['dashboard_layout'];
      }
      else
      {
          $data = ['status_code' => 404];
          $view = 'error/client_error';
      }
      $data['widget'] = app(Widgets::class);
      mr_view($view, $data);

    }
}
