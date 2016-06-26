<?php

// This migration converts the old munkireport table to a new version
// most of the data is migrated to managedinstalls

class Migration_munkireport_new extends Model
{
    // Drop these columns
    protected $dropCols = array(
                'managedinstalls',
                'pendinginstalls',
                'installresults',
                'removalresults',
                'failedinstalls',
                'pendingremovals',
                'itemstoinstall',
                'appleupdates',
                'report_plist',
                'runstate',
            );
            
    // Add these columns
    protected $addcols = array(
                'error_json' => 'BLOB',
                'warning_json' => 'BLOB',
            ); 
    
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('id', 'munkireport'); //primary key, tablename        
    }

    
    public function up()
    {
        // Get database handle
        $dbh = $this->getdbh();

        
        // Wrap in transaction
        $dbh->beginTransaction();
        
        // ***** Move content from report_plist to managedinstalls module
        
        // Check if we have compression support
        $compress = function_exists('gzdeflate');
        
        // Instantiate managedinstalls model
        $managedinstalls = new Managedinstalls_model;
                
        // Get managedinstalls instance
        
        // Get all reports (make faster by only moving data of machines that
        // don't have entries in managedinstalls)
        $sql = "SELECT munkireport.serial_number, report_plist 
                FROM munkireport 
                LEFT JOIN managedinstalls USING(serial_number)
                WHERE managedinstalls.serial_number IS NULL AND report_plist != ''";
        foreach($dbh->query($sql) as $arr)
        {
            $report = unserialize( $this->COMPRESS_ARRAY ? gzinflate( $arr['report_plist'] ) : $arr['report_plist'] );
            $install_list = array();
            if(isset($report['ManagedInstalls'])){
                $this->add_items($report['ManagedInstalls'], $install_list, 'installed', 'munki');
            }
            if(isset($report['AppleUpdates'])){
                $this->add_items($report['AppleUpdates'], $install_list, 'pending_install', 'applesus');
            }
            if(isset($report['ProblemInstalls'])){
                $this->add_items($report['ProblemInstalls'], $install_list, 'install_failed', 'munki');
            }
            if(isset($report['ItemsToRemove'])){
                $this->add_items($report['ItemsToRemove'], $install_list, 'pending_removal', 'munki');
            }
            if(isset($report['ItemsToInstall'])){
                $this->add_items($report['ItemsToInstall'], $install_list, 'pending_install', 'munki');
            }
            // Removed items
            if(isset($report['RemovedItems'])){
                $this->add_removeditems($report['RemovedItems'], $install_list);
            }

            // Update install_list with results
            if(isset($report['RemovalResults'])){
                $this->remove_result($report['RemovalResults'], $install_list);
            }
            if(isset($report['InstallResults'])){
                $this->install_result($report['InstallResults'], $install_list);
            }
                        
            // Store data in managedinstalls
            $managedinstalls->setSerialNumber($arr['serial_number']);
            $managedinstalls->processData($install_list);

        }

        // ***** Modify columns

        if ($this->get_driver() == 'mysql')
        {
            // ***** Add columns
            foreach($this->addcols as $colname => $type){
                $sql = sprintf('ALTER TABLE %s ADD COLUMN %s %s',
                    $this->tablename, $colname, $type);
                $this->exec($sql);
            }
            
            // Drop columns
            $sql = sprintf('ALTER TABLE munkireport DROP %s', implode(', DROP ', $this->dropCols));
            $this->exec($sql); 
            
            // Create indexes
            foreach ($addcols as $key ) {
                $sql = sprintf('CREATE INDEX munkireport_%s ON munkireport (%s)', $key, $key);
                $this->exec($sql); 
            }
            
        }
        else{
            throw new Exception("SQLite migration not ready", 1);
        }
        
        // Commit transaction
        $dbh->commit();
        
        //throw new Exception("Error Processing Request", 1);
        
        

    }// End function up()

    /**
     * Migrate down
     *
     * Migrates this table to the previous version
     *
     **/
    public function down()
    {
        
        // No down
    }
    
    /**
     * Add items
     */
    public function add_items($item_list, &$install_list, $status, $item_type)
    {
        foreach($item_list as $item){
            // Check if applesus item
            if(isset($item['productKey'])){
                $name = $item['productKey'];
            }
            else{
                $name = $item['name'];
            }
            
            $install_list[$name] = $this->filter_item($item);
            $install_list[$name]['status'] = $status;
            $install_list[$name]['type'] = $item_type;
            
        }
    }
    
    // """Add removed item to list and set status"""
    public function add_removeditems($item_list, &$install_list){
        
        foreach($item_list as $item){
            $install_list[$item] = array('name' => $item, 'status' => 'removed',
                'installed'=> 0, 'display_name'=> $item, 'type'=> 'munki');
        }
    }
    
    // """Update list according to result"""
    public function remove_result($item_list, &$install_list){
        
        foreach($item_list as $item){
            #install_list[item['name']]['time'] = item.time
            $listItem = &$install_list[$item['name']];
            
            if ($item['status'] == 0){
                $listItem['installed'] = false;
                $listItem['status'] = 'uninstalled';
            }
            else{
                $listItem['status'] = 'uninstall_failed';
            }
            // Sometimes an item is only in RemovalResults, so we have to add
            // extra info:
            
            // Add munki
            $listItem['type'] = 'munki';
            
            // Fix display name
            if( ! isset($listItem['display_name'])){
                $listItem['display_name'] = $item['display_name'];
            }
        }
    }
    
    // """Update list according to result"""
    public function install_result($item_list, &$install_list){
        foreach($item_list as $item){
            #install_list[item['name']]['time'] = item.time
            
            // Check if applesus item
            if(isset($item['productKey'])){
                $name = $item['productKey'];
                // Store extra props
                $install_list[$name]['display_name'] = $item['name'];
                $install_list[$name]['version'] = $item['version'];
                $install_list[$name]['type'] = 'applesus';
            }
            else{
                $name = $item['name'];
            }
                
            if ($item['status'] == 0){
                $install_list[$name]['installed'] = true;
                $install_list[$name]['status'] = 'installed';
            }
            else{
                $install_list[$name]['status'] = 'install_failed';
            }
        }
    }
    
    // """Only return specified keys"""
    public function filter_item($item){
        $keys = array("display_name", "installed_version", "installed_size",
                "version_to_install", "installed", "note");

        $out = array();
        foreach ($keys as $key) {
            if(isset($item[$key])){
                $out[$key] = $item[$key];
            }
        }

        return $out;
    }

}