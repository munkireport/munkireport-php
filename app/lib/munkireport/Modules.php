<?php

namespace munkireport\lib;

use Symfony\Component\Yaml\Yaml;

/**
* Modules class
*
* Retrieves info about installed modules
*
*/
class Modules
{
    /**
     * @var array The complete list of available modules with their listings, widgets, etc.
     */
    private $moduleList = [];

    /**
     * @var array The complete list of available widgets (probably superseded by Widgets class)
     */
    private $widgetList = [];

    /**
     * @var array A list of paths that may be scanned for v5 modules.
     */
    private $moduleSearchPaths = [];

    /**
     * @var string[] A list of modules that are always enabled effectively, without being declared.
     */
    private $allowedModules = [
      'machine',
      'reportdata',
      'tag',
      'event',
      'comment',
    ];

    /**
     * @var bool When generating a list of modules, whether to skip modules not explicitly enabled.
     */
    private $skipInactiveModules = False;

    public function __construct()
    {
        // Populate allowedModules if hide_inactive_modules is true
        if(conf('hide_inactive_modules')){
            $this->skipInactiveModules = True;
        }
        
        // Module search paths from config
        $moduleSearchPaths = [];
        if(is_array(conf('module_search_paths'))){
            $moduleSearchPaths = conf('module_search_paths');
        }
        // And then local modules
        $moduleSearchPaths[] = conf('local') . 'modules/';
        // And then built-in modules
        $moduleSearchPaths[] = conf('module_path');
        
        foreach ($moduleSearchPaths as $path) {
            if(is_dir($path)){
                $this->moduleSearchPaths[] = rtrim($path, '/') . '/';
            }
        }

        $this->addCoreModules();
    }

    /**
     * Add modules that are part of core MunkiReport.
     *
     * This is a temporary workaround for the fact that listings and processors can't be discovered inside
     * the core package. When theres another registration mechanism, this can be removed.
     *
     * Widgets have been omitted because addModuleWidgets() always assumes views are in the same path as the module.
     * There is a function called addCoreWidgets() which adds them separately.
     */
    protected function addCoreModules()
    {
        $this->moduleList['reportdata'] = [
            'detail_widgets' => [
                'network_detail' => ['view' => 'network_detail_widget'],
            ],
            'listings' => array(
                'clients' => array('view' => 'clients_listing', 'i18n' => 'client.clients'),
            ),
//            'widgets' => array()
//                'client' => array('view' => 'client_widget'),
//                'registered_clients' => array('view' => 'registered_clients_widget'),
//                'uptime' => array('view' => 'uptime_widget'),
//            ),
            'reports' => array(
                'clients' => array('view' => 'clients', 'i18n' => 'client.report'),
            ),
            'path' => realpath(__DIR__ . '../'),
            'core' => true,
        ];

        $this->moduleList['machine'] = [
            'detail_widgets' => [
                'machine_info_1' => ['view' => 'machine_detail_widget1'],
                'machine_info_2' => ['view' => 'machine_detail_widget2'],
                'hardware_detail' => ['view' => 'hardware_detail_widget'],
                'software_detail' => ['view' => 'software_detail_widget'],
            ],
            'listings' => [
                'hardware' => ['view' => 'hardware_listing', 'i18n' => 'machine.hardware'],
            ],
//            'widgets' => [
//                'duplicated_computernames' => ['view' => 'duplicated_computernames_widget'],
//                'hardware_basemodel' => ['view' => 'hardware_basemodel_widget'],
//                'hardware_model' => ['view' => 'hardware_model_widget'],
//                'hardware_type' => ['view' => 'hardware_type_widget'],
//                'installed_memory' => ['view' => 'installed_memory_widget'],
//                'memory' => ['view' => 'memory_widget'],
//                'new_clients' => ['view' => 'new_clients_widget'],
//                'os' => ['view' => 'os_widget'],
//                'osbuild' => ['view' => 'osbuild_widget'],
//            ],
            'reports' => [
                'hardware' => ['view' => 'hardware', 'i18n' => 'machine.hardware_report'],
            ],
            'path' => realpath(__DIR__ . '../'),
            'core' => true,
        ];
        
        $this->moduleList['tag'] = [
            'listings' => [
                'tag' => ['view' => 'tag_listing', 'i18n' => 'tag.tag'],
            ],
            'widgets' => [
                'tag' => ['view' => 'tag_widget'],
            ],
            'reports' => [
                'tag' => ['view' => 'tag_report', 'i18n' => 'tag.listing.title'],
            ],
            'path' => realpath(__DIR__ . '../'),
            'core' => true,
        ];

        $this->moduleList['comment'] = [
            'detail_widgets' => [
                    'comment_detail' => [ 'view' => 'comment_detail_widget'],
            ],
            'path' => realpath(__DIR__ . '../'),
            'core' => true,
        ];

        $this->moduleList['event'] = [
            'widgets' => [
                'messages' => ['view' => 'messages_widget'],
            ],
            'listings' => [
                'event' => ['view' => 'event_listing', 'i18n' => 'event.event_plural'],
            ],
            'path' => realpath(__DIR__ . '../'),
            'core' => true,
        ];

    }

    /**
     * Retrieve moduleSearchPaths
     *
     * @return array
     */
    public function getModuleSearchPaths()
    {
        return $this->moduleSearchPaths;
    }

    /**
     * Retrieve moduleModelPath
     *
     * @todo $modelPath should never be passed as null, but it is sometimes.
     */
    public function getModuleModelPath(string $moduleName, ?string &$modelPath): bool
    {
        foreach ($this->moduleSearchPaths as $type => $path) {
            if ( file_exists($path . $moduleName . '/' . $moduleName . '_model.php')) {
                $modelPath = $path . $moduleName . '/' . $moduleName . '_model.php';
                return True;
            }
        }
        return False;
    }

    /**
     * Retrieve moduleProcessorPath
     *
     * @todo $modelPath should never be passed as null, but it is sometimes.
     */
    public function getModuleProcessorPath(string $moduleName, ?string &$modelPath): bool
    {
        foreach ($this->moduleSearchPaths as $type => $path) {
            if ( file_exists($path . $moduleName . '/' . $moduleName . '_processor.php')) {
                $modelPath = $path . $moduleName . '/' . $moduleName . '_processor.php';
                return True;
            }
        }
        return False;
    }

    /**
     * Retrieve moduleControllerPath
     */
    public function getmoduleControllerPath(string $moduleName, ?string &$controllerPath): bool
    {
        foreach ($this->moduleSearchPaths as $type => $path) {
            if ( file_exists($path . $moduleName . '/' . $moduleName . '_controller.php')) {
                $controllerPath = $path . $moduleName . '/' . $moduleName . '_controller.php';
                return True;
            }
        }
        return False;
    }

    /**
     * Retrieve moduleMigrationPath
     */
    public function getModuleMigrationPath(string $moduleName, ?string &$migrationPath): bool
    {
        foreach ($this->moduleSearchPaths as $type => $path) {
            if (is_dir($path . $moduleName . '/migrations')) {
                $migrationPath = $path . $moduleName . '/migrations';
                return True;
            }
        }
        return False;
    }

    /**
     * Retrieve list of all available modules
     */
    public function getModuleList(bool $all_modules = false): array
    {
        $modules = [];
        foreach ($this->moduleSearchPaths as $path)
        {
            foreach (scandir($path) as $module)
            {
                if (($all_modules && is_file($path.$module.'/'.$module.'_model.php')) || is_file($path.$module.'/scripts/install.sh')) {
                    // Don't overwrite custom modules
                    if( ! isset($modules[$module]))
                    {
                        $modules[$module] = $path.$module;
                    }
                }
            }
        }
        return $modules;
    }

    /**
     * Load Module info
     *
     * Load info from provides.php
     *
     * @param boolean $allModules If true, don't mind $skipInactiveModules
     */
    public function loadInfo(bool $allModules = False): Modules
    {
        if($allModules){
            $skipInactiveModules = False;
        }else{
            $skipInactiveModules = $this->skipInactiveModules;
        }

        $this->collectModuleInfo(
            $this->moduleSearchPaths,
            $skipInactiveModules,
            $this->getAllowedModules($skipInactiveModules));

        return $this;

    }

    /**
     * @param bool $skipInactiveModules
     * @return array|string|string[]
     */
    public function getAllowedModules(bool $skipInactiveModules)
    {
        if($skipInactiveModules){
            return array_merge($this->allowedModules, conf('modules', []));
        }
        return [];
    }

    /**
     * Get information about one or all modules.
     *
     * @param string $about
     * @return array
     */
    public function getInfo(string $about = ""): array
    {
        if( ! $about){
            return $this->moduleList;
        }

        $out = [];
        foreach ($this->moduleList as $module => $moduleInfo) {
            if(array_key_exists($about, $moduleInfo)){
                $out[$module] = $moduleInfo[$about];
            }
        }

        return $out;
    }

    /**
     * Get listing info based on module and name
     *
     * @param string $module Name of module
     * @param string $name Name of listing
     * @return bool|object
     */
    public function getListing(string $module, string $name)
    {
        if(isset($this->moduleList[$module]['listings'])){
            if( isset($this->moduleList[$module]['listings'][$name]['view'])) {
                return (object) [
                    'view_path' => $this->getPath($module, '/views/'),
                    'view' => $this->moduleList[$module]['listings'][$name]['view'],
                    'module' => $module, // Added in v6 if you would like to use view namespacing.
                ];
            }
        }
        return False;
    }

    /**
     * Get report info based on module and name
     *
     * @param string $module Name of module
     * @param string $name Name of listing
     * @return bool|object
     */
    public function getReport(string $module, string $name)
    {
        if(isset($this->moduleList[$module]['reports'][$name])){
            return (object) [
                'view_path' => $this->getPath($module, '/views/'),
                'view' => $this->moduleList[$module]['reports'][$name]['view'],
                'module' => $module, // Added in v6 if you would like to use view namespacing.
                'type' => $this->getType(
                    $this->getPath($module, '/views/'),
                    $this->moduleList[$module]['reports'][$name]['view']
                ),
            ];
        }
        return False;
    }

    /**
     * Determine whether the view/widget is yaml or php
     *
     * @param string $path
     * @param string $view
     * @return string 'yaml' or 'php'
     */
    private function getType(string $path, string $view): string
    {
        return is_readable( $path . $view . '.yml') ? 'yaml' : 'php';
    }

    /**
     * Get the module path or a path relative to a module.
     *
     * @param string $module The name of the module to generate a path for.
     * @param string $append A string to append to the path.
     * @return false|string Returns false if the module doesnt exist.
     */
    public function getPath(string $module, string $append = '')
    {
        // Temporary workaround which allows core modules to reside in the laravel app path, but still allows
        // mr_view to `include()` the view files.
        if (isset($this->moduleList[$module]['core']) && $this->moduleList[$module]['core'] === true) {
            return resource_path('views') . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR; // . $append;
        }

        if( isset( $this->moduleList[$module]['path'])){
            return $this->moduleList[$module]['path'] . $append;
        }
        return False;
    }

    /**
     * Add detail tabs from modules to the array passed by reference
     *
     * @param array &$tabArray An array that should have module tabs appended.
     */
    public function addTabs(array &$tabArray): void
    {
        foreach( $this->getInfo('client_tabs') as $module => $client_tabs){
            foreach($client_tabs as $id => $info){
                $info['view_path'] = $this->getPath($module, '/views/');
                $info['module'] = $module; // Added in v6 to hint the view namespace
                $tabArray[$id] = $info;
            }
        }
    }

    /**
     * Add widgets from modules to the array passed by reference
     *
     * @todo This seems unused, maybe classes are using \munkireport\lib\Widgets?
     * @param array &$widgetArray An array that should have module widgets appended.
     */
    public function addWidgets(array &$widgetArray, ?array $detailWidgetList = [])
    {
        $tempList = [];
        foreach( $this->getInfo('detail_widgets') as $module => $detail_widgets){
            foreach($detail_widgets as $id => $info){
                $info['view_path'] = $this->getPath($module, '/views/');
                $info['module'] = $module; // Added in v6 to hint the view namespace
                $tempList[$id] = $info;
            }
        }
        // Order widgets according to $detailWidgetList
        if($detailWidgetList){
            foreach($detailWidgetList as $widgetId){
                if(isset($tempList[$widgetId])){
                    $widgetArray[$widgetId] = $tempList[$widgetId];
                    unset($tempList[$widgetId]);
                }
            }
            // If last widget is not * remove rest of the widgets
            if($widgetId != '*'){
                $tempList = [];
            }
        }
        foreach($tempList as $id => $info){
            $widgetArray[$id] = $info;
        }
    }
    

    /**
     * Get data to create dropdown nav
     *
     * @param string $kind 'reports' or 'listings'
     * @param string $baseUrl 'show/report' or 'show/listing'
     * @param string $page current page url path
     * @return array
     */
    public function getDropdownData(string $kind, string $baseUrl, string $page): array
    {
        $out = [];
        foreach( $this->getInfo($kind) as $module => $kindArray){
            foreach($kindArray as $itemName => $itemData){
                if(isset($itemData['hide_from_menu']) && $itemData['hide_from_menu']){
                    continue;
                }
                $i18n = isset($itemData['i18n']) ? $itemData['i18n'] : 'nav.' . $kind . '.' . $itemName;
                $out[] = (object) [
                  'url' => mr_url($baseUrl.'/'.$module.'/'.$itemName),
                  'name' => $itemName,
                  'class' => $page == $baseUrl.'/'.$module.'/'.$itemName ? 'active' : '',
                  'i18n' => $i18n,
                ];
            }
        }

        return $out;
    }

    /**
     * Returns all widgets for widget gallery
     *
     * @author tuxudo
     */
    public function getWidgets(): array
    {        
        // Get list of all the modules that contain a widget
        $out = array();
        foreach ($this->moduleList as $module => $moduleInfo) {
            if(array_key_exists('widgets', $moduleInfo)){
                $out[$module] = $moduleInfo['widgets'];
            }
        }
        
        $active_modules = $this->getAllowedModules($skipInactiveModules = true);
           
        // Generate list for widget gallery
        foreach( $out as $module => $widgets){
            foreach($widgets as $id => $info){
                // Found a widget, add it to widgetList
                $this->widgetList[$id] = (object) array(
                    'widget_file' => str_replace(array(APP_ROOT,"//"),array('','/'),$this->getPath($module, '/views/')),
                    'name' => $id,
                    'view' => $info['view'],
                    'path' => $this->getPath($module, '/views/'),
                    'type' => $this->getType(
                        $this->getPath($module, '/views/'),
                        $info['view']
                    ),
                    'module' => $module,
                    'vars' => '',
                    'active' => in_array($module, $active_modules),
                );
            }
        }
        
        // Sort the widgets by widget name
        usort($this->widgetList, function($a, $b){
            return strcmp($a->name, $b->name);
        });
        return $this->widgetList;
    }

    /**
     * Get a JSON encoded combined locales file for the specified language, for all modules.
     *
     * @param string $lang
     * @return string JSON encoded locale strings for all modules.
     */
    public function getModuleLocales(string $lang='en'): string
    {
        $localeList = [];
        foreach( $this->moduleList as $module => $info){
            $localePath = sprintf('%s/locales/%s.json', $info['path'], $lang);
            if(is_file($localePath)){
                $localeList[] = sprintf('"%s": %s', $module, file_get_contents($localePath));
            }
        }
        return '{'.implode(",\n", $localeList).'}';
    }

    /**
     * Collect information from all `provides.(php|yaml)` files in all v5 modules.
     *
     * @param array $modulePaths An array of module search paths, where each path contains directories representing modules.
     * @param bool $skipInactiveModules Do not return information about modules that are not enabled.
     * @param array $allowedModules An array of modules which are always searched even if disabled, such as core modules.
     */
    private function collectModuleInfo(array $modulePaths, bool $skipInactiveModules = False, array $allowedModules = []): void
    {
        foreach ($modulePaths as $basePath)
        {
            if( ! is_dir($basePath)){
                // Emit warning?
                continue;
            }
            foreach (scandir($basePath) as $module) {

                // Skip inactive modules
                if( $skipInactiveModules && ! in_array($module, $allowedModules)){
                    continue;
                }

                // Find provides.php
                if (is_file($basePath.$module.'/provides.yml') && ! isset($this->moduleList[$module]))
                {
                    // Load YAML data
                    try {
                        $this->moduleList[$module] = Yaml::parseFile($basePath.$module.'/provides.yml');
                        $this->moduleList[$module]['path'] = $basePath.$module.'/';
                    } catch (\Throwable $th) {
                        error($th->getMessage());
                    }
                }
                elseif (is_file($basePath.$module.'/provides.php') && ! isset($this->moduleList[$module]))
                {
                    $this->moduleList[$module] = require $basePath.$module.'/provides.php';
                    $this->moduleList[$module]['path'] = $basePath.$module.'/';
                }
            }
        }
    }

    /**
     * Add a module to the list of modules available.
     *
     * @param string $moduleName
     * @param array $metadata
     */
    public function add(string $moduleName, array $metadata) {
        $this->moduleList[$moduleName] = $metadata;
    }
}
