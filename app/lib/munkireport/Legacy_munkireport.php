<?php

namespace munkireport\lib;

/**
 * Utility for legacy munkireport
 *
 * Converts standard ManagedInstalls plist to something
 * used in 004_munkireport_new migration and munkireport_model
 *
 *
 */
class Legacy_munkireport
{
    
    private $install_list = array();
    
    public function __construct()
    {
    }
    
    /**
     * Getter for install_list
     *
     * @return array list of install items
     */
    public function getList()
    {
        return $this->install_list;
    }
    
    /**
     * Parse ManagedInstallResult.plist
     *
     * Convert to new format
     *
     * @param type var Description
     * @return {11:return type}
     */
    public function parse(&$report)
    {
        
        if (isset($report['ManagedInstalls'])) {
            $this->add_items($report['ManagedInstalls'], 'installed', 'munki');
        }
        if (isset($report['AppleUpdates'])) {
            $this->add_items($report['AppleUpdates'], 'pending_install', 'applesus');
        }
        if (isset($report['ProblemInstalls'])) {
            $this->add_items($report['ProblemInstalls'], 'install_failed', 'munki');
        }
        if (isset($report['ItemsToRemove'])) {
            $this->add_items($report['ItemsToRemove'], 'pending_removal', 'munki');
        }
        if (isset($report['ItemsToInstall'])) {
            $this->add_items($report['ItemsToInstall'], 'pending_install', 'munki');
        }
        // Removed items
        if (isset($report['RemovedItems'])) {
            $this->add_removeditems($report['RemovedItems']);
        }

        // Update install_list with results
        if (isset($report['RemovalResults'])) {
            $this->remove_result($report['RemovalResults']);
        }
        if (isset($report['InstallResults'])) {
            $this->install_result($report['InstallResults']);
        }
        
        return $this;
    }

    /**
     * Add items
     */
    public function add_items($item_list, $status, $item_type)
    {
        foreach ($item_list as $item) {
            // Check if applesus item
            if (isset($item['productKey'])) {
                $name = $item['productKey'];
            } else {
                $name = $item['name'];
            }
            
            $this->install_list[$name] = $this->filter_item($item);
            $this->install_list[$name]['status'] = $status;
            $this->install_list[$name]['type'] = $item_type;
        }
    }
    
    // """Add removed item to list and set status"""
    public function add_removeditems($item_list)
    {
        
        foreach ($item_list as $item) {
            $this->install_list[$item] = array('name' => $item, 'status' => 'removed',
                'installed'=> 0, 'display_name'=> $item, 'type'=> 'munki');
        }
    }
    
    // """Update list according to result"""
    public function remove_result($item_list)
    {
        
        foreach ($item_list as $item) {
            #install_list[item['name']]['time'] = item.time
            $listItem = &$this->install_list[$item['name']];
            
            if ($item['status'] == 0) {
                $listItem['installed'] = false;
                $listItem['status'] = 'uninstalled';
            } else {
                $listItem['status'] = 'uninstall_failed';
            }
            // Sometimes an item is only in RemovalResults, so we have to add
            // extra info:
            
            // Add munki
            $listItem['type'] = 'munki';
            
            // Fix display name
            if (! isset($listItem['display_name'])) {
                $listItem['display_name'] = $item['display_name'];
            }
        }
    }
    
    // """Update list according to result"""
    public function install_result($item_list)
    {
        foreach ($item_list as $item) {
            #install_list[item['name']]['time'] = item.time
            
            // Check if applesus item
            if (isset($item['productKey'])) {
                $name = $item['productKey'];
                // Store extra props
                $this->install_list[$name]['display_name'] = $item['name'];
                $this->install_list[$name]['version'] = $item['version'];
                $this->install_list[$name]['type'] = 'applesus';
            } else {
                $name = $item['name'];
            }
                
            if ($item['status'] == 0) {
                $this->install_list[$name]['installed'] = true;
                $this->install_list[$name]['status'] = 'install_succeeded';
            } else {
                $this->install_list[$name]['status'] = 'install_failed';
            }
        }
    }
    
    // """Only return specified keys"""
    public function filter_item($item)
    {
        $keys = array("display_name", "installed_version", "installed_size",
                "version_to_install", "installed", "note");

        $out = array();
        foreach ($keys as $key) {
            if (isset($item[$key])) {
                $out[$key] = $item[$key];
            }
        }

        return $out;
    }
}
