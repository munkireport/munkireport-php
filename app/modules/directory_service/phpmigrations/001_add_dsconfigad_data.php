<?php

class Migration_add_dsconfigad_data extends \Model
{
    
    public function up()
    {
        // Get database handle
        $dbh = $this->getdbh();

        // Check if database is already migrated
        // (to fix issue with failed migration init)
        try {
            // This will cause an Exception if not migrated
            $dbh->query("SELECT adforest FROM directoryservice");
            return true;
        } catch (Exception $e) {
        // Not migrated, continue..
        }

        switch ($this->get_driver()) {
            case 'sqlite':
                try {
                    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Wrap in transaction
                    $dbh->beginTransaction();

                    // Create new columns one at a time. Faster to do new table?
                    $sql = "ALTER TABLE directoryservice ADD COLUMN adforest VARCHAR(255)";
                    $dbh->exec($sql);
                    $sql = "ALTER TABLE directoryservice ADD COLUMN addomain VARCHAR(255)";
                    $dbh->exec($sql);
                    $sql = "ALTER TABLE directoryservice ADD COLUMN computeraccount VARCHAR(255)";
                    $dbh->exec($sql);
                    $sql = "ALTER TABLE directoryservice ADD COLUMN createmobileaccount BOOL";
                    $dbh->exec($sql);
                    $sql = "ALTER TABLE directoryservice ADD COLUMN requireconfirmation BOOL";
                    $dbh->exec($sql);
                    $sql = "ALTER TABLE directoryservice ADD COLUMN forcehomeinstartup BOOL";
                    $dbh->exec($sql);
                    $sql = "ALTER TABLE directoryservice ADD COLUMN mounthomeassharepoint BOOL";
                    $dbh->exec($sql);
                    $sql = "ALTER TABLE directoryservice ADD COLUMN usewindowsuncpathforhome BOOL";
                    $dbh->exec($sql);
                    $sql = "ALTER TABLE directoryservice ADD COLUMN networkprotocoltobeused VARCHAR(255)";
                    $dbh->exec($sql);
                    $sql = "ALTER TABLE directoryservice ADD COLUMN defaultusershell VARCHAR(255)";
                    $dbh->exec($sql);
                    $sql = "ALTER TABLE directoryservice ADD COLUMN mappinguidtoattribute VARCHAR(255)";
                    $dbh->exec($sql);
                    $sql = "ALTER TABLE directoryservice ADD COLUMN mappingusergidtoattribute VARCHAR(255)";
                    $dbh->exec($sql);
                    $sql = "ALTER TABLE directoryservice ADD COLUMN mappinggroupgidtoattr VARCHAR(255)";
                    $dbh->exec($sql);
                    $sql = "ALTER TABLE directoryservice ADD COLUMN generatekerberosauth BOOL";
                    $dbh->exec($sql);
                    $sql = "ALTER TABLE directoryservice ADD COLUMN preferreddomaincontroller VARCHAR(255)";
                    $dbh->exec($sql);
                    $sql = "ALTER TABLE directoryservice ADD COLUMN allowedadmingroups VARCHAR(255)";
                    $dbh->exec($sql);
                    $sql = "ALTER TABLE directoryservice ADD COLUMN authenticationfromanydomain BOOL";
                    $dbh->exec($sql);
                    $sql = "ALTER TABLE directoryservice ADD COLUMN packetsigning VARCHAR(255)";
                    $dbh->exec($sql);
                    $sql = "ALTER TABLE directoryservice ADD COLUMN packetencryption VARCHAR(255)";
                    $dbh->exec($sql);
                    $sql = "ALTER TABLE directoryservice ADD COLUMN passwordchangeinterval VARCHAR(255)";
                    $dbh->exec($sql);
                    $sql = "ALTER TABLE directoryservice ADD COLUMN restrictdynamicdnsupdates VARCHAR(255)";
                    $dbh->exec($sql);
                    $sql = "ALTER TABLE directoryservice ADD COLUMN namespacemode VARCHAR(255)";
                    $dbh->exec($sql);

                    $dbh->commit();
                } catch (Exception $e) {
                    $dbh->rollBack();
                    $this->errors .= "Failed: " . $e->getMessage();
                    return false;
                }
                break;

            case 'mysql':
                // Create new columns
                $sql = "ALTER TABLE directoryservice
						ADD adforest VARCHAR(255),
						ADD addomain VARCHAR(255),
						ADD computeraccount VARCHAR(255),
						ADD createmobileaccount BOOL,
						ADD requireconfirmation BOOL,
						ADD forcehomeinstartup BOOL,
						ADD mounthomeassharepoint BOOL,
						ADD usewindowsuncpathforhome BOOL,
						ADD networkprotocoltobeused VARCHAR(255),
						ADD defaultusershell VARCHAR(255),
						ADD mappinguidtoattribute VARCHAR(255),
						ADD mappingusergidtoattribute VARCHAR(255),
						ADD mappinggroupgidtoattr VARCHAR(255),
						ADD generatekerberosauth BOOL,
						ADD preferreddomaincontroller VARCHAR(255),
						ADD allowedadmingroups VARCHAR(255),
						ADD authenticationfromanydomain BOOL,
						ADD packetsigning VARCHAR(255),
						ADD packetencryption VARCHAR(255),
						ADD passwordchangeinterval VARCHAR(255),
						ADD restrictdynamicdnsupdates VARCHAR(255),
						ADD namespacemode VARCHAR(255)";
                $dbh->exec($sql);

                break;

            default:
                # code...
                break;
        }
    }// End function up()

    public function down()
    {
        // Get database handle
        $dbh = $this->getdbh();

        switch ($this->get_driver()) {
            case 'sqlite':
                try {
                    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Wrap in transaction
                    $dbh->beginTransaction();

                    // Create a new temporary table
                    $sql = "CREATE TABLE directoryservice_temp (
								id INTEGER PRIMARY KEY, 
								serial_number VARCHAR(255) UNIQUE, 
								which_directory_service VARCHAR(255), 
								directory_service_comments VARCHAR(255))";
                    $this->query($sql);

                    // Copy values
                    $sql = "INSERT INTO directoryservice_temp SELECT id,serial_number,which_directory_service,directory_service_comments FROM directoryservice";
                    $this->query($sql);
                    
                    $sql = "DROP table directoryservice";
                    $this->query($sql);
                    
                    $sql = "ALTER TABLE directoryservice_temp RENAME TO directoryservice";
                    $this->query($sql);

                    $dbh->commit();
                } catch (Exception $e) {
                    $dbh->rollBack();
                    $this->errors .= "Failed: " . $e->getMessage();
                    return false;
                }
                break;

            case 'mysql':
                // Drop the new columns
                $sql = "ALTER TABLE directoryservice
						DROP adforest,
						DROP addomain,
						DROP computeraccount,
						DROP createmobileaccount,
						DROP requireconfirmation,
						DROP forcehomeinstartup,
						DROP mounthomeassharepoint,
						DROP usewindowsuncpathforhome,
						DROP networkprotocoltobeused,
						DROP defaultusershell,
						DROP mappinguidtoattribute,
						DROP mappingusergidtoattribute,
						DROP mappinggroupgidtoattr,
						DROP generatekerberosauth,
						DROP preferreddomaincontroller,
						DROP allowedadmingroups,
						DROP authenticationfromanydomain,
						DROP packetsigning,
						DROP packetencryption,
						DROP passwordchangeinterval,
						DROP restrictdynamicdnsupdates,
						DROP namespacemode";
                $dbh->query($sql);

                break;

            default:
                # code...
                break;
        }
    }
}
