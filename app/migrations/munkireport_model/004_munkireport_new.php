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
        throw new Exception("Migration disabled - please revert to previous version of munkireport", 1);
        
        
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
                
        // Lock tables
        if ($this->get_driver() == 'mysql'){
            $sql = "LOCK TABLES munkireport WRITE, managedinstalls WRITE";
            $this->exec($sql);
        }

        
        // Get limited
        $sql = "SELECT serial_number, report_plist 
                FROM munkireport 
                WHERE report_plist != ''
                LIMIT 50";
        if($resultset = $this->query($sql)){
            foreach($resultset as $arr)
            {
                $report = unserialize( $this->COMPRESS_ARRAY ? gzinflate( $arr->report_plist ) : $arr->report_plist );
                
                // Load legacy support TODO: use autoloader
                include_once (APP_PATH . '/lib/munkireport/Legacy_munkireport.php');
                $legacyObj = new munkireport\Legacy_munkireport;
                $install_list = $legacyObj->parse($report)->getList();
                
                // Store data in managedinstalls
                $managedinstalls->setSerialNumber($arr->serial_number);
                $managedinstalls->processData($install_list);
                
                // Save errors and warnings
                if(isset($report['Errors'])){
                    $sql = sprintf("UPDATE munkireport SET error_json = ? WHERE serial_number = '%s'",
                                $arr->serial_number);
                    $stmt = $this->prepare( $sql );
                    $this->execute($stmt, array(json_encode($report['Errors'])));
                }
                
                if(isset($report['Warnings'])){
                    $sql = sprintf("UPDATE munkireport SET warning_json = ? WHERE serial_number = '%s'",
                                $arr->serial_number);
                    $stmt = $this->prepare( $sql );
                    $this->execute($stmt, array(json_encode($report['Warnings'])));
                }
                
                // Reset report_plist
                $sql = sprintf("UPDATE munkireport SET report_plist = '' WHERE serial_number = '%s'",
                                $arr->serial_number);
                $this->exec($sql);
            }
            
            
            // Prevent migration to be marked complete
            throw new Exception("Still converting entries", 1);
        }

        // ***** Modify columns

        if ($this->get_driver() == 'mysql')
        {            
            // Drop columns
            $sql = sprintf('ALTER TABLE munkireport DROP %s', implode(', DROP ', $this->dropCols));
            $this->exec($sql);             
        }
        else{
            //throw new Exception("SQLite migration not ready", 1);
            
            // Create a temporary table
            $sql = "CREATE TABLE munkireport_temp (
                        id INTEGER PRIMARY KEY,
                        serial_number VARCHAR(255) UNIQUE,
                        timestamp VARCHAR(255),
                        runtype VARCHAR(255),
                        starttime VARCHAR(255),
                        endtime varchar(255),
                        version varchar(255),
                        errors int(11),
                        warnings int(11),
                        manifestname varchar(255),
                        error_json BLOB,
                        warning_json BLOB)";
            $this->exec($sql);
            
            // Copy everything to the temp table
            $sql = "INSERT INTO munkireport_temp 
                        SELECT id, serial_number, timestamp, runtype, starttime, endtime, version, errors, warnings, manifestname, error_json, warning_json FROM munkireport";
            $this->exec($sql);
            
            // Drop original table
            $sql = "DROP table munkireport";
            $this->exec($sql);
            
            // Rename temp table
            $sql = "ALTER TABLE munkireport_temp RENAME TO munkireport";
            $this->exec($sql);

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