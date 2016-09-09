<?php

// Add UNIQUE index to serial_number column

class Migration_munkireport_add_unique_index extends Model
{
    
    public function up()
    {
        // Get database handle
        $dbh = $this->getdbh();

        $sql = 'CREATE UNIQUE INDEX serial_number ON munkireport (serial_number)';
        $this->exec($sql);
    }// End function up()

    public function down()
    {
        // Get database handle
        $dbh = $this->getdbh();

        switch ($this->get_driver()) {
            case 'sqlite':
                $sql = 'DROP INDEX munkireport.serial_number';

                break;

            case 'mysql':
                $sql = 'DROP INDEX serial_number ON munkireport';

                break;

            default:
                throw new Exception("UNKNOWN DRIVER");
        }

        // Execute sql query
        $this->exec($sql);
    }
}
