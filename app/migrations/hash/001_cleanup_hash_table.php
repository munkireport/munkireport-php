<?php


/**
 * Cleanup hash table
 *
 * Cleaning up old entries from the hash table
 * as some of the models have been renamed
 * (old entries only get removed when the entire machine is removed
 * or the hash table is reset)
 *
 * @package default
 * @author
 **/
class Migration_cleanup_hash_table extends \Model
{
    
    public function up()
    {
        try {
            // Get database handle
            $dbh = $this->getdbh();

            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Wrap in transaction
            $dbh->beginTransaction();

            // List of legacy model names and their replacements
            $rename_list = array(
                'InstallHistory' => 'installhistory',
                'Machine' => 'machine',
                'InventoryItem' => 'inventory',
                'inventoryitem' => 'inventory',
                'Munkireport' => 'munkireport',
                'Reportdata' => 'reportdata',
                'filevault_status_model' => 'filevault_status',
                'localadmin_model' => 'localadmin',
                'network_model' => 'network',
                'disk_report_model' => 'disk_report'
            );

            foreach ($rename_list as $old => $new) {
                $sql = "UPDATE hash SET `name` = '$new' WHERE `name` = '$old'";
                $dbh->exec($sql);
            }

            $dbh->commit();
        } catch (Exception $e) {
            $dbh->rollBack();
            $this->errors .= "Failed: " . $e->getMessage();
            return false;
        }
    }// End function up()

    public function down()
    {
        // We can't go back to the previous version
        // But that is ok.
    }
}
