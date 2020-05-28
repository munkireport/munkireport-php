<?php

namespace munkireport\controller;

use \Controller, \View, \Model;
use munkireport\lib\Request;
use Symfony\Component\Yaml\Yaml;

class Module_marketplace extends Controller
{
    private $moduleMarketplace;

    public function __construct()
    {
        // Check authorization
        $this->authorized() || jsonError('Authenticate first', 403);
        $this->authorized('global') || jsonError('You need to be admin', 403);

        // Create object
        $this->moduleMarketplace = getMrModuleObj();
    }

    /**
     * Install all modules
     *
     * @author Bochoven, A.E. van
     **/
    public function index()
    {
        echo "You've loaded the module marketplace!";
    }

    /**
     * Processes and returns the composer.local.lock file for admin tab
     *
     * @author tuxudo
     **/
    public function get_module_data()
    {
        // Use composer.local.lock over composer.lock file
        if(file_exists(__DIR__ . '../../../composer.local.lock')){
            $composer_pkgs = json_decode(file_get_contents(__DIR__ . '../../../composer.local.lock'), true)['packages'];
        } else {
            $composer_pkgs = json_decode(file_get_contents(__DIR__ . '../../../composer.lock'), true)['packages'];
        }

        $all_modules = $this->moduleMarketplace->getModuleList(true);
        $enabled_modules = conf('modules', array());
        $composer_modules = [];
        $composer_modules_full = [];
        $composer_modules_name = array();
        $i = 0;

        // Process each package
        foreach ($composer_pkgs as $pkg) {
            // Process each munkireport or munkireport module package
            if (strpos(strtolower($pkg['description']), 'module for munkireport') !== false || substr( $pkg['name'], 0, 12 ) === "munkireport/"){

                $name_array = explode("/",$pkg['name']);

                array_push($composer_modules_name, $name_array[1]);
                array_push($composer_modules_full, $pkg['name']);
                $composer_modules[$i]["module"] = $name_array[1];
                $composer_modules[$i]["module_full"] = $pkg['name'];
                $composer_modules[$i]["maintainer"] = $name_array[0];
                $composer_modules[$i]["url"] = str_replace(".git","",$pkg['source']['url']);
                $composer_modules[$i]["installed"] = 1;
                $composer_modules[$i]["latest_version"] = "";
                $composer_modules[$i]["date_updated"] = "";
                $composer_modules[$i]["update_available"] = "";

                // Check if the version string has a 'v' in it, if not append it
                if (substr(strtolower($pkg['version']), 0, 1) !== 'v') {
                    $composer_modules[$i]["installed_version"] = "v".$pkg['version'];
                } else {
                    $composer_modules[$i]["installed_version"] = strtolower($pkg['version']);
                }

                // Check if core module
                if ($name_array[0] == "munkireport"){
                    $composer_modules[$i]["core"] = 1;
                } else {
                    $composer_modules[$i]["core"] = 0;
                }

                // Check if in all modules
                if (array_key_exists($name_array[1], $all_modules)){
                    $composer_modules[$i]["module_location"] = str_replace("//","/",$all_modules[$name_array[1]]);
                    
                    if(strpos($all_modules[$name_array[1]], 'vendor/'.$name_array[0]) === false){
                        $composer_modules[$i]["custom_override"] = 1;
                    } else {
                        $composer_modules[$i]["custom_override"] = 0;
                    }

                } else {
                    // I wonder if bochoven will review this part
                    $composer_modules[$i]["module_location"] = "";
                    $composer_modules[$i]["custom_override"] = "";
                }

                // Check if enabled
                if (in_array($name_array[1], $enabled_modules)){
                    $composer_modules[$i]["enabled"] = 1;
                } else if ($name_array[1] == "comment" || $name_array[1] == "event" || $name_array[1] == "machine" || $name_array[1] == "tag" || $name_array[1] == "reportdata"){
                    // Override for system modules
                    $composer_modules[$i]["enabled"] = 1;
                } else {
                    $composer_modules[$i]["enabled"] = 0;
                }

                // Get timestamp of module's model file
                if(file_exists($composer_modules[$i]["module_location"]."/".$name_array[1]."_model.php")){
                    $composer_modules[$i]["date_downloaded"] = filemtime ($composer_modules[$i]["module_location"]);
                } else {
                    $composer_modules[$i]["date_downloaded"] = "";
                }

                $i++;
            }
        }

        // Process non-composer modules
        foreach ($all_modules as $module=>$location) {
            // Extract non-composer modules
            if (!in_array($module, $composer_modules_name)){
                $composer_modules[$i]["module"] = $module;
                $composer_modules[$i]["maintainer"] = "";
                $composer_modules[$i]["module_full"] = "";
                $composer_modules[$i]["url"] = "";
                $composer_modules[$i]["installed_version"] = "";
                $composer_modules[$i]["core"] = 0;
                $composer_modules[$i]["module_location"] = str_replace("//","/",$location);
                $composer_modules[$i]["custom_override"] = 1;
                $composer_modules[$i]["latest_version"] = "";
                $composer_modules[$i]["update_available"] = "";
                $composer_modules[$i]["date_updated"] = "";
                $composer_modules[$i]["installed"] = 1;

                // Get timestamp of custom module's model file
                if(file_exists($location."/".$module."_model.php")){
                    $composer_modules[$i]["date_downloaded"] = filemtime ($location);
                } else {
                    $composer_modules[$i]["date_downloaded"] = "";
                }

                // Check if enabled
                if (in_array($module, $enabled_modules)){
                    $composer_modules[$i]["enabled"] = 1;
                } else {
                    $composer_modules[$i]["enabled"] = 0;
                }

                $i++;
            }
        }

        $repo_modules = Module_marketplace::get_module_repos();

        // Check if we have a result
        if($repo_modules){

            foreach ($repo_modules as $repo_module) {

                // Extract modules that are not installed
                if (!in_array($repo_module, $composer_modules_full)){

                    $name_array = explode("/",$repo_module);

                    $composer_modules[$i]["module"] = $name_array[1];
                    $composer_modules[$i]["module_full"] = $repo_module;
                    $composer_modules[$i]["maintainer"] = $name_array[0];
                    $composer_modules[$i]["url"] = "https://github.com/".$repo_module;
                    $composer_modules[$i]["installed_version"] = "";
                    $composer_modules[$i]["module_location"] = "";
                    $composer_modules[$i]["custom_override"] = "";
                    $composer_modules[$i]["latest_version"] = "";
                    $composer_modules[$i]["update_available"] = "";
                    $composer_modules[$i]["date_updated"] = "";
                    $composer_modules[$i]["date_downloaded"] = "";
                    $composer_modules[$i]["installed"] = 0;
                    $composer_modules[$i]["enabled"] = "";

                    // Check if core module
                    if ($name_array[0] == "munkireport"){
                        $composer_modules[$i]["core"] = 1;
                    } else {
                        $composer_modules[$i]["core"] = 0;
                    }

                    $i++;
                }
            }
        }

        // Return array of composer installed modules
        jsonView($composer_modules);
    }

    /**
     * Returns information on modules' scripts
     *
     * @author tuxudo
     **/
    public function get_module_script_info()
    {
        $all_modules = $this->moduleMarketplace->getModuleList();
        $modules = [];
        $i = 0;

        // Process each module
        foreach ($all_modules as $path) {

            $all_files = scandir($path."/scripts/");
            $files = array_diff($all_files, array('.', '..','install.sh','uninstall.sh'));

            // Process each file in scripts directory
            foreach ($files as $file) {
                if (strpos(strtolower($file), '.zip') === false && substr( $file, 0, 1 ) !== "."){

                    $module_name = explode("/",$path);
                    $modules[$i]["module"] = end($module_name);
                    $modules[$i]["path"] = $path."/scripts/".$file;
                    $modules[$i]["script"] = $file;
                    $modules[$i]["date_modified"] = filemtime($modules[$i]["path"]);
                    $modules[$i]["date_modified_human"] = date("F j, Y, g:i a", $modules[$i]["date_modified"]);

                    // Get script type
                    $line1 = fgets(fopen($modules[$i]["path"], 'r'));
                    $file_line1 = explode("/",$line1);
                    $modules[$i]["script_type"] = str_replace("\n","",end($file_line1));

                    $i++;
                }
            }
        }

        // Return JSON of script details
        jsonView($modules);
    }

    /**
     * Returns information on a module's UI views
     *
     * @author tuxudo
     **/
    public function get_module_info($module){

        // Load up the modules
        $modules = $this->moduleMarketplace->loadInfo(true);

        // Return info on single module as JSON
        jsonView($modules->getInfo()[$module]);
    }

    /**
     * Gets and processes YAML from GitHub that contains Packagist repos
     * Pulls from munkireport and tuxudo's fork of munkireport-php
     *
     * @author tuxudo
     **/
    public function get_module_repos()
    {
        // Get JSON from munkireport-php's GitHub
        $web_request = new Request();
        $options = ['http_errors' => false];
        $yaml_result = (string) $web_request->get('https://raw.githubusercontent.com/munkireport/munkireport-php/master/build/module_repos.yml', $options);

        // Check if we got results
        if (strpos($yaml_result, 'core_modules: munkireport/') === false ){
            $yaml_result = file_get_contents(__DIR__ . '../../../build/module_repos.yml');
        }

        // Process yaml files
        $yaml_data = Yaml::parse($yaml_result);
        $core_modules = explode(",", preg_replace("/[^A-Za-z0-9-_,\/]/", '', $yaml_data['core_modules']));
        $third_party_modules = explode(",", preg_replace("/[^A-Za-z0-9-_,\/]/", '', $yaml_data['third_party_modules']));

        // Get JSON from tuxudo's GitHub to merge in
        $web_request = new Request();
        $yaml_result_tux = (string) $web_request->get('https://raw.githubusercontent.com/tuxudo/munkireport-php/master/build/module_repos.yml', $options);

        // Check if we got results
        if (strpos($yaml_result, 'core_modules: munkireport/') !== false ){

            $yaml_data_tux = Yaml::parse($yaml_result_tux);

            $core_data_tux = explode(",", preg_replace("/[^A-Za-z0-9-_,\/]/", '', $yaml_data_tux['core_modules']));
            $third_party_modules_tux = explode(",", preg_replace("/[^A-Za-z0-9-_,\/]/", '', $yaml_data_tux['third_party_modules']));

            $core_modules = array_unique(array_merge($core_modules, $core_data_tux));
            $third_party_modules = array_unique(array_merge($third_party_modules, $third_party_modules_tux));
        }

        // Return list of module repos if true
        return array_unique(array_merge($core_modules, $third_party_modules));
    }
}
