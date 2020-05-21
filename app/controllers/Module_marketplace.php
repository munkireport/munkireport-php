<?php

namespace munkireport\controller;

use \Controller, \View, \Model;
use munkireport\models\Module_marketplace_model;
use Symfony\Component\Yaml\Yaml;
use munkireport\models\Cache;

class Module_marketplace extends Controller
{
    private $moduleMarketplace;

    public function __construct()
    {
        $this->moduleMarketplace = getMrModuleObj();

        // Connect to database
        $this->connectDB();
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
    public function get_composer_lock()
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
            if (strpos($pkg['description'], 'module for munkireport') !== false || substr( $pkg['name'], 0, 12 ) === "munkireport/"){
                
                $name_array = explode("/",$pkg['name']);

                array_push($composer_modules_name, $name_array[1]);
                array_push($composer_modules_full, $pkg['name']);
                $composer_modules[$i]["module"] = $name_array[1];
                $composer_modules[$i]["maintainer"] = $name_array[0];
                $composer_modules[$i]["url"] = str_replace(".git","",$pkg['source']['url']);
                $composer_modules[$i]["installed"] = 1;
                
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
                    $composer_modules[$i]["date_downloaded"] = filemtime ($composer_modules[$i]["module_location"]."/".$name_array[1]."_model.php");
                } else {
                    $composer_modules[$i]["date_downloaded"] = "";
                }
                
                // Get data from database of matching module
                $sql_obj = new Model;
                $sql = "SELECT * FROM modules
                        WHERE module = '".$pkg['name']."';";
                $result = $sql_obj->query($sql);
                
                // Check if we have a result
                if($result && $result[0] && $result[0]->module){

                    $installed_version = preg_replace("/[^0-9.]/", '', $composer_modules[$i]["installed_version"]);
                    $latest_version = preg_replace("/[^0-9.]/", '', $result[0]->version);

                    // Check versions for updates
                    if (version_compare($latest_version, $installed_version, '>')){
                        $composer_modules[$i]["update_available"] = 1;
                    } else {
                        $composer_modules[$i]["update_available"] = 0;
                    }

                    $composer_modules[$i]["latest_version"] = $result[0]->version;
                    $composer_modules[$i]["date_updated"] = (int) ($result[0]->date_updated);
                } else {
                    $composer_modules[$i]["latest_version"] = "";
                    $composer_modules[$i]["date_updated"] = "";
                    $composer_modules[$i]["update_available"] = "";
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
                    $composer_modules[$i]["date_downloaded"] = filemtime ($location."/".$module."_model.php");
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

        // Process modules that are not installed
        $sql_obj = new Model;
        $sql = "SELECT module FROM modules;";
        $repo_modules = $sql_obj->query($sql);

        // Check if we have a result
        if($repo_modules){
        
            $out = array();
            foreach ($repo_modules as $obj) {
                $out[] = $obj->module;
            }

            foreach ($out as $repo_module) {

                // Extract modules that are not installed
                if (!in_array($repo_module, $composer_modules_full)){

                    // Get data from database of matching module
                    $sql_obj = new Model;
                    $sql = "SELECT * FROM modules
                        WHERE module = '".$repo_module."';";
                    $result = $sql_obj->query($sql);

                    $name_array = explode("/",$repo_module);

                    $composer_modules[$i]["module"] = $name_array[1];
                    $composer_modules[$i]["maintainer"] = $result[0]->maintainer;
                    $composer_modules[$i]["url"] = $result[0]->url;
                    $composer_modules[$i]["installed_version"] = "";
                    $composer_modules[$i]["module_location"] = "";
                    $composer_modules[$i]["custom_override"] = "";
                    $composer_modules[$i]["latest_version"] = $result[0]->version;
                    $composer_modules[$i]["update_available"] = "";
                    $composer_modules[$i]["date_updated"] = $result[0]->date_updated;
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
    
    public function get_module_info($module){

        // Load up the modules
        $modules = $this->moduleMarketplace->loadInfo(true);

        // Return info on single module as JSON
        jsonView($modules->getInfo()[$module]);
    }
    
    /**
     * Gets and processes JSON from Packagist and puts it into the database
     *
     * @author tuxudo
     **/
    public function refresh_module_info()
    {
        // Update module repos
        Module_marketplace::update_module_repos();

        // Generate list of all modules    
        $core_modules = Cache::select('value')->where('module', 'module_marketplace')->where('property', 'core_modules')->value('value');
        $third_party_modules = Cache::select('value')->where('module', 'module_marketplace')->where('property', 'third_party_modules')->value('value');
        $all_modules = array_unique(array_merge(explode(",",$core_modules), explode(",",$third_party_modules)));

        foreach ( $all_modules as $module){
            // Get JSON from Packagist for module
            ini_set("allow_url_fopen", 1);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, 'https://repo.packagist.org/p/'.$module.'.json');
            $json_result = curl_exec($ch);

            // Check if we got results
            if (strpos($json_result, '"packages":{"'.$module.'":') === false ){
                print_r("Unable to get Packagist JSON for module ".$module."!");
                return false;
            }

            // Extract the latest version
            $module_json = end(json_decode($json_result, true)['packages'][$module]);

            $name_array = explode("/",$module_json['name']);

            $module_pkg = new Module_marketplace_model($name_array[1]);
            $module_pkg->module = $module_json['name'];
            $module_pkg->maintainer = $name_array[0];
            $module_pkg->url = str_replace(".git","",$module_json['source']['url']);
            $module_pkg->date_updated = strtotime($module_json['time']);

            // Check if the version string has a 'v' in it, if not append it
            if (substr(strtolower($module_json['version']), 0, 1) !== 'v') {
                $module_pkg->version = "v".$module_json['version'];
            } else {
                $module_pkg->version = strtolower($module_json['version']);
            }

            // Check if core module
            if ($name_array[0] == "munkireport"){
                $module_pkg->core = 1;
            } else {
                $module_pkg->core = 0;
            }

            $module_pkg->packagist = 1;

            // Delete previous row containing matching module
            $module_pkg->deleteWhere('module=?', $module_json['name']);

            // Modules are like Legos! :D
            $module_pkg->save();
        }

        // Return a status
        jsonView('status:good');
    }

    /**
     * Gets and processes YAML from GitHub that contains Packagist repos
     * Pulls from munkireport and tuxudo's fork of munkireport-php
     *
     * @author tuxudo
     **/
    public function update_module_repos()
    {
        // Get JSON from munkireport-php's GitHub
        ini_set("allow_url_fopen", 1);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, 'https://raw.githubusercontent.com/munkireport/munkireport-php/master/build/module_repos.yml');
        $yaml_result = curl_exec($ch);

        // Check if we got results
        if (strpos($yaml_result, 'core_modules: munkireport/') === false ){
            $yaml_result = file_get_contents(__DIR__ . '../../../build/module_repos.yml');
        }
        
        // Process yaml files
        $yaml_data = Yaml::parse($yaml_result);
        $core_modules = explode(",", preg_replace("/[^A-Za-z0-9-_,\/]/", '', $yaml_data['core_modules']));
        $third_party_modules = explode(",", preg_replace("/[^A-Za-z0-9-_,\/]/", '', $yaml_data['third_party_modules']));
        
        // Get JSON from tuxudo's GitHub to merge in
        ini_set("allow_url_fopen", 1);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, 'https://raw.githubusercontent.com/tuxudo/munkireport-php/master/build/module_repos.yml');
        $yaml_result_tux = curl_exec($ch);

        // Check if we got results
        if (strpos($yaml_result, 'core_modules: munkireport/') !== false ){

            $yaml_data_tux = Yaml::parse($yaml_result_tux);
            
            $core_data_tux = explode(",", preg_replace("/[^A-Za-z0-9-_,\/]/", '', $yaml_data_tux['core_modules']));
            $third_party_modules_tux = explode(",", preg_replace("/[^A-Za-z0-9-_,\/]/", '', $yaml_data_tux['third_party_modules']));
            
            $core_modules = array_unique(array_merge($core_modules, $core_data_tux));
            $third_party_modules = array_unique(array_merge($third_party_modules, $third_party_modules_tux));
        }
        
        // Save new cache data to the cache table
        Cache::updateOrCreate(
            [
                'module' => 'module_marketplace', 
                'property' => 'core_modules',
            ],[
                'value' => implode(",",$core_modules),
                'timestamp' => time(),
            ]
        );
        Cache::updateOrCreate(
            [
                'module' => 'module_marketplace', 
                'property' => 'third_party_modules',
            ],[
                'value' => implode(",",$third_party_modules),
                'timestamp' => time(),
            ]
        );
    }
}
