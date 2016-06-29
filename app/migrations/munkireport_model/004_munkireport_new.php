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
        
        // ***** Add columns
        
        try {
            foreach($this->addcols as $colname => $type){
                $sql = sprintf('ALTER TABLE %s ADD COLUMN %s %s',
                    $this->tablename, $colname, $type);
                $this->exec($sql);
            }
        } catch (Exception $e) {
            // We do nothing here
        }
        

        
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
            
            // Load legacy support TODO: use autoloader
            include_once (APP_PATH . '/lib/munkireport/Legacy_munkireport.php');
            $legacyObj = new munkireport\Legacy_munkireport;
            $install_list = $legacyObj->parse($report)->getList();
            
            // Store data in managedinstalls
            $managedinstalls->setSerialNumber($arr['serial_number']);
            $managedinstalls->processData($install_list);
            
            // Save errors and warnings
            if(isset($report['Errors'])){
                $sql = sprintf("UPDATE munkireport SET error_json = ? WHERE serial_number = '%s'",
                            $arr['serial_number']);
                $this->query($sql, array(json_encode($report['Errors']))); 
            }
            
            if(isset($report['Warnings'])){
                $sql = sprintf("UPDATE munkireport SET warning_json = ? WHERE serial_number = '%s'",
                            $arr['serial_number']);
                $this->query($sql, array(json_encode($report['Warnings']))); 
            }

        }

        // ***** Modify columns

        if ($this->get_driver() == 'mysql')
        {            
            // Drop columns
            $sql = sprintf('ALTER TABLE munkireport DROP %s', implode(', DROP ', $this->dropCols));
            $this->exec($sql);             
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
    


}