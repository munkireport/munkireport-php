<?php

namespace munkireport\lib;

use App\Packages;
use Illuminate\Foundation\PackageManifest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
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
    private array $moduleList = [];

    /**
     * @var array The complete list of available widgets (probably superseded by Widgets class)
     */
    private array $widgetList = [];

    /**
     * @var array A list of paths that may be scanned for v5 modules.
     */
    private array $moduleSearchPaths = [];

    /**
     * @var string[] A list of modules that are always enabled effectively, without being declared.
     */
    private array $allowedModules = [
      'machine',
      'reportdata',
      'tag',
      'event',
      'comment',
    ];

    /**
     * @var bool When generating a list of modules, whether to skip modules not explicitly enabled.
     */
    private bool $skipInactiveModules = False;

    public function __construct()
    {
        // Populate allowedModules if hide_inactive_modules is true
        if(config('_munkireport.hide_inactive_modules', false)){
            $this->skipInactiveModules = True;
        }
        
        // Module search paths from config
        $moduleSearchPaths = [];
        if(is_array(conf('module_search_paths'))){
            $moduleSearchPaths = conf('module_search_paths');
        }
        // And then local modules
        $moduleSearchPaths[] = config('_munkireport.local') . 'modules/';
        // And then built-in modules
        $moduleSearchPaths[] = config('_munkireport.module_path');
        
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
    protected function addCoreModules(): void
    {
        $this->moduleList['reportdata'] = [
            'detail_widgets' => [
                'network_detail' => ['view' => 'network_detail_widget'],
            ],
            'listings' => array(
                'clients' => array('view' => 'clients_listing', 'i18n' => 'client.clients'),
//                'clients' => array('url' => url('/clients'), 'i18n' => 'client.clients'),
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
                'comment_detail' => [
                    'version' => 6,
                    'component' => 'widget.detail.comments',
//                    'view' => 'comment_detail_widget',
                    'id' => 'comment-widget',
                    'icon' => 'comment-o',
                    'i18n_title' => 'client.comment'
                ]
            ],
            'path' => realpath(__DIR__ . '../'),
            'core' => true,
        ];

        $this->moduleList['event'] = [
            'widgets' => [
                'messages' => ['view' => 'messages_widget'],
            ],
            'listings' => [
                'event' => ['view' => 'event_listing', 'i18n' => 'events.event_plural'],
            ],
            'path' => realpath(__DIR__ . '../'),
            'core' => true,
        ];

        // These listings/menus were client side only for no real reason, they have been
        // consolidated into the Module Manager so that the sorting only needs to happen once.
        $this->moduleList['core'] = [
            'core' => true,
            'path' => realpath(__DIR__ . '../'),
            'admin_pages' => [
                'systemstatus' => ['i18n' => 'systemstatus.menu_link', 'url' => url('/system/status')],
                'system.database' => ['i18n' => 'system.database.menu_link', 'url' => url('/system/database')],
                'widget.gallery' => ['i18n' => 'widget.gallery', 'url' => url('/system/widgets')],
                'module_marketplace' => ['i18n' => 'module_marketplace.module_marketplace', 'url' => url('/module_marketplace')],
            ]
        ];
    }

    /**
     * Retrieve moduleSearchPaths
     *
     * @return array
     */
    public function getModuleSearchPaths(): array
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
     * - v6.0.0: Added loading from module cache to speed up page loads.
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

        if (Storage::disk('local')->exists('modules.json')) {
            $cache = Storage::disk('local')->get('modules.json');

            if ($skipInactiveModules) {
                $this->moduleList = Arr::only(json_decode($cache, true), $this->getAllowedModules(true));
            } else {
                $this->moduleList = json_decode($cache, true);
            }
        } else {
            $this->collectModuleInfo(
                $this->moduleSearchPaths,
                $skipInactiveModules,
                $this->getAllowedModules($skipInactiveModules));
        }

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
     * @param string $about The name of a module to get information about, leave empty for all modules.
     * @return array An array of all module metadata, or just the metadata for the module name specified in the about parameter.
     */
    public function getInfo(string $about = ""): array
    {
        if(!$about) {
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
    public function getListing(string $module, string $name): bool|object
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
    public function getReport(string $module, string $name): bool|object
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
    public function getPath(string $module, string $append = ''): bool|string
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
     * As of v6, the URL to the target is not automatically always $base/$module/$item.
     * Each module may provide an extra key as the link of the dropdown item:
     *
     * * `url` - an absolute URL to use for the link
     * * `route` - a route alias to use for the link
     *
     * If both of these are missing, the behaviour falls back to v5 (the link is constructed using the module name)
     * 
     * @param string $kind 'reports' or 'listings' or 'admin_pages'
     * @param string $baseUrl 'show/report' or 'show/listing'
     * @param string $page current page url path
     * @return array An array of (object) that contain a `url`, `name`, `class` and `i18n` property, sorted alphabetically.
     */
    public function getDropdownData(string $kind, string $baseUrl, string $page): array
    {
        $out = [];
        $items = $this->getInfo($kind);
        ksort($items);

        foreach($items as $module => $kindArray){
            foreach($kindArray as $itemName => $itemData){
                if(isset($itemData['hide_from_menu']) && $itemData['hide_from_menu']){
                    continue;
                }
                $i18n = $itemData['i18n'] ?? 'nav.' . $kind . '.' . $itemName;

                if (isset($itemData['url'])) {
                    $url = $itemData['url'];
                } else if (isset($itemData['route'])) {
                    $url = route($itemData['route']);
                } else {
                    $url = mr_url($baseUrl.'/'.$module.'/'.$itemName);
                }

                $out[] = (object) [
                  'url' => $url,
                  'name' => $itemName,
                  'class' => $page == $url ? 'active' : '',
                  'i18n' => $i18n,
                ];
            }
        }

        $sorted = collect($out)->sortBy('name', SORT_NATURAL)->toArray();

        return $sorted;
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
        
        $active_modules = $this->getAllowedModules(true);
           
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

        $this->collectPackageInformation();
    }

    /**
     * Collect module information from Laravel-style MunkiReport v6 Packages
     *
     * The package information is stored in a backwards-compatible way so that the listings/dropdowns abstractions
     * work just like v5.
     *
     * Locale keys are translated from their composer package name eg. munkireport/package to an underscored version
     * eg 'munkireport_package' for use with i18next.
     *
     * @since 6.0.0
     * @return void
     */
    private function collectPackageInformation(): void
    {
        $packages = new Packages();
        $modulePackages = $packages->modules();

        foreach ($modulePackages as $mp) {
            if (Arr::has($mp->getExtra(), 'munkireport')) {
                $meta = $mp->getExtra()['munkireport'];
                $this->moduleList[str_replace( '/', '_', $mp->getName())] = [
                    'admin_pages' => Arr::get($meta, 'navigation.admin_pages', []),
                    'listings' => Arr::get($meta, 'navigation.listings', []),
                    'reports' => Arr::get($meta, 'navigation.reports', []),
                    'detail_widgets' => Arr::get($meta, 'detail_widgets', []),
                    'widgets' => Arr::get($meta, 'widgets', []),
                    'path' => realpath(base_path($mp->getDistUrl())),
                ];
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
