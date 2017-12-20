<?php

class Migration_bluetooth_model_columns_to_integers extends \Model
{

    public function up()
    {
        // Get database handle
        $dbh = $this->getdbh();
        
        //Set status to binary
        $sql = "UPDATE bluetooth SET bluetooth_status = 1 WHERE bluetooth_status = 'Bluetooth is on '";
        $dbh->exec($sql);
        $sql = "UPDATE bluetooth SET bluetooth_status = 0 WHERE bluetooth_status = 'Bluetooth is off '";
        $dbh->exec($sql);
        $sql = "UPDATE bluetooth SET bluetooth_status = '-1' WHERE bluetooth_status = 'Bluetooth is '";
        $dbh->exec($sql);
        //Set disconnected to -1
        $sql = "UPDATE bluetooth SET keyboard_battery = '-1' WHERE keyboard_battery = 'Disconnected '";
        $dbh->exec($sql);
        $sql = "UPDATE bluetooth SET mouse_battery = '-1' WHERE mouse_battery = 'Disconnected '";
        $dbh->exec($sql);
        $sql = "UPDATE bluetooth SET trackpad_battery = '-1' WHERE trackpad_battery = 'Disconnected'";
        $dbh->exec($sql);
        
        // Convert percentages to INTEGER
        $sql = "UPDATE bluetooth SET keyboard_battery = REPLACE(keyboard_battery, '% battery life remaining ', '')";
        $dbh->exec($sql);
        $sql = "UPDATE bluetooth SET mouse_battery = REPLACE(mouse_battery, '% battery life remaining ', '')";
        $dbh->exec($sql);
        $sql = "UPDATE bluetooth SET trackpad_battery = REPLACE(trackpad_battery, '% battery life remaining', '')";
        $dbh->exec($sql);
        
        switch ($this->get_driver()) {
            case 'sqlite':
                try {
                    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Wrap in transaction
                    $dbh->beginTransaction();
                    
                    // Create temporary table
                    $sql = "CREATE TABLE bluetooth_temp (`id` INTEGER PRIMARY KEY AUTOINCREMENT,`serial_number` VARCHAR(255) UNIQUE,`bluetooth_status` INT,`keyboard_battery` INT,`mouse_battery` INT,`trackpad_battery` INT)";
                    $dbh->exec($sql);
                    
                    // Copy data to temp table
                    $sql = "INSERT INTO bluetooth_temp SELECT * FROM bluetooth";
                    $dbh->exec($sql);
                    
                    $sql = "DROP table bluetooth";
                    $dbh->exec($sql);

                    $sql = "ALTER TABLE bluetooth_temp RENAME TO bluetooth";
                    $dbh->exec($sql);

                    $dbh->commit();
                } catch (Exception $e) {
                    $dbh->rollBack();
                    $this->errors .= "Failed: " . $e->getMessage();
                    return false;
                }
                
                break;

            case 'mysql':
                // Set columns to INT
                $sql = "ALTER TABLE bluetooth MODIFY bluetooth_status INT";
                $dbh->exec($sql);
                $sql = "ALTER TABLE bluetooth MODIFY keyboard_battery INT";
                $dbh->exec($sql);
                $sql = "ALTER TABLE bluetooth MODIFY mouse_battery INT";
                $dbh->exec($sql);
                $sql = "ALTER TABLE bluetooth MODIFY trackpad_battery INT";
                $dbh->exec($sql);
                break;

            default:
                # code...
                break;
        }//end switch
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
                    
                    // Create temporary table
                    $sql = "CREATE TABLE `bluetooth_temp` (`id` INTEGER PRIMARY KEY AUTOINCREMENT,`serial_number` VARCHAR(255) UNIQUE,`bluetooth_status` VARCHAR(255),`keyboard_battery` VARCHAR(255),`mouse_battery` VARCHAR(255),`trackpad_battery` VARCHAR(255))";
                    $dbh->exec($sql);
                    
                    // Copy data to temp table
                    $sql = "INSERT INTO bluetooth_temp SELECT * FROM bluetooth";
                    $dbh->exec($sql);
                    
                    $sql = "DROP table bluetooth";
                    $dbh->exec($sql);

                    $sql = "ALTER TABLE bluetooth_temp RENAME TO bluetooth";
                    $dbh->exec($sql);
                    
                    //Set status back to strings
                    $sql = "UPDATE bluetooth SET bluetooth_status = 'Bluetooth is on ' WHERE bluetooth_status='1'";
                    $dbh->exec($sql);
                    $sql = "UPDATE bluetooth SET bluetooth_status = 'Bluetooth is off ' WHERE bluetooth_status='0'";
                    $dbh->exec($sql);
                    $sql = "UPDATE bluetooth SET bluetooth_status = 'Bluetooth is ' WHERE bluetooth_status='-1'";
                    $dbh->exec($sql);

                    //Replace -1 with disconnected
                    $sql = "UPDATE bluetooth SET keyboard_battery = 'Disconnected ' WHERE keyboard_battery='-1'";
                    $dbh->exec($sql);
                    $sql = "UPDATE bluetooth SET mouse_battery = 'Disconnected ' WHERE mouse_battery='-1'";
                    $dbh->exec($sql);
                    $sql = "UPDATE bluetooth SET trackpad_battery = 'Disconnected' WHERE trackpad_battery='-1'";
                    $dbh->exec($sql);
                    
                    //Put the text back
                    $sql = "UPDATE bluetooth SET keyboard_battery = keyboard_battery || '% battery life remaining ' WHERE keyboard_battery NOT LIKE 'Disconnected '";
                    $dbh->exec($sql);
                    $sql = "UPDATE bluetooth SET mouse_battery = mouse_battery || '% battery life remaining ' WHERE mouse_battery NOT LIKE 'Disconnected '";
                    $dbh->exec($sql);
                    //next one didn't end in whitespace
                    $sql = "UPDATE bluetooth SET trackpad_battery = trackpad_battery || '% battery life remaining' WHERE trackpad_battery NOT LIKE 'Disconnected'";
                    $dbh->exec($sql);

                    $dbh->commit();
                } catch (Exception $e) {
                    $dbh->rollBack();
                    $this->errors .= "Failed: " . $e->getMessage();
                    return false;
                }
                break;

            case 'mysql':
                // Set columns back to VARCHAR
                $sql = "ALTER TABLE bluetooth MODIFY bluetooth_status VARCHAR(255)";
                $dbh->exec($sql);
                $sql = "ALTER TABLE bluetooth MODIFY keyboard_battery VARCHAR(255)";
                $dbh->exec($sql);
                $sql = "ALTER TABLE bluetooth MODIFY mouse_battery VARCHAR(255)";
                $dbh->exec($sql);
                $sql = "ALTER TABLE bluetooth MODIFY trackpad_battery VARCHAR(255)";
                $dbh->exec($sql);
                
                //Set status back to strings
                $sql = "UPDATE bluetooth SET bluetooth_status = 'Bluetooth is on ' WHERE bluetooth_status='1'";
                $dbh->exec($sql);
                $sql = "UPDATE bluetooth SET bluetooth_status = 'Bluetooth is off ' WHERE bluetooth_status='0'";
                $dbh->exec($sql);
                $sql = "UPDATE bluetooth SET bluetooth_status = 'Bluetooth is ' WHERE bluetooth_status='-1'";
                $dbh->exec($sql);

                //Replace -1 with disconnected
                $sql = "UPDATE bluetooth SET keyboard_battery = 'Disconnected ' WHERE keyboard_battery='-1'";
                $dbh->exec($sql);
                $sql = "UPDATE bluetooth SET mouse_battery = 'Disconnected ' WHERE mouse_battery='-1'";
                $dbh->exec($sql);
                $sql = "UPDATE bluetooth SET trackpad_battery = 'Disconnected' WHERE trackpad_battery='-1'";
                $dbh->exec($sql);

                //Put the text back
                $sql = "UPDATE bluetooth SET keyboard_battery = CONCAT(keyboard_battery , '% battery life remaining ') WHERE keyboard_battery NOT LIKE 'Disconnected '";
                $dbh->exec($sql);
                $sql = "UPDATE bluetooth SET mouse_battery = CONCAT(mouse_battery , '% battery life remaining ') WHERE mouse_battery NOT LIKE 'Disconnected '";
                $dbh->exec($sql);
                //next one didn't end in whitespace
                $sql = "UPDATE bluetooth SET trackpad_battery = CONCAT(trackpad_battery , '% battery life remaining') WHERE trackpad_battery NOT LIKE 'Disconnected'";
                $dbh->exec($sql);

                break;

            default:
                # code...
                break;
        }
    }
}
