<?php

class Migration_smart_stats_add_nvme_columns extends \Model
{
    protected $new_columns = array(
        'pci_vender_subsystem_id' => 'VARCHAR(255)',
        'critical_warning' => 'VARCHAR(255)',
        'available_spare' => 'BIGINT',
        'available_spare_threshold' => 'BIGINT',
        'percentage_used' => 'BIGINT',
        'data_units_read' => 'VARCHAR(255)',
        'data_units_written' => 'VARCHAR(255)',
        'host_read_commands' => 'BIGINT',
        'host_write_commands' => 'BIGINT',
        'controller_busy_time' => 'BIGINT',
        'unsafe_shutdowns' => 'BIGINT',
        'media_data_integrity_errors' => 'BIGINT',
        'error_info_log_entries' => 'BIGINT',
        'ieee_oui_id' => 'VARCHAR(255)',
        'controller_id' => 'BIGINT',
        'number_of_namespaces' => 'BIGINT',
        'firmware_updates' => 'VARCHAR(255)',
        'optional_admin_commands' => 'TEXT',
        'optional_nvm_commands' => 'TEXT',
        'max_data_transfer_size' => 'VARCHAR(255)',
    );

    public function __construct()
    {
        parent::__construct();
        $this->tablename = 'smart_stats';
    }

    public function up()
    {
        // Get database handle
        $dbh = $this->getdbh();

        // Wrap in transaction
        $dbh->beginTransaction();

        // Adding a column is simple...
        foreach ($this->new_columns as $column => $type) {
            $sql = "ALTER TABLE smart_stats ADD COLUMN $column $type";
            $this->exec($sql);
        }

        $dbh->commit();
    }

    public function down()
    {
        // Can't go back. Just like we can't go back to the 1964 New York World's Fair and ride the Greyhound people movers :/
    }
}
