<?php
class Deploystudio_model extends \Model
{
    
    protected $error = '';
    protected $module_dir;
    
    public function __construct($serial = '')
    {
        parent::__construct('id', 'deploystudio'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial;
        $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
        $this->rs['architecture'] = '';
        $this->rs['cn'] = '';
        $this->rs['dstudio_auto_disable'] = '';
        $this->rs['dstudio_auto_reset_workflow'] = '';
        $this->rs['dstudio_auto_started_workflow'] = '';
        $this->rs['dstudio_bootcamp_windows_computer_name'] = '';
        $this->rs['dstudio_disabled'] = '';
        $this->rs['dstudio_group'] = '';
        $this->rs['dstudio_host_ard_field_1'] = '';
        $this->rs['dstudio_host_ard_field_2'] = '';
        $this->rs['dstudio_host_ard_field_3'] = '';
        $this->rs['dstudio_host_ard_field_4'] = '';
        $this->rs['dstudio_host_ard_ignore_empty_fields'] = '';
        $this->rs['dstudio_host_delete_other_locations'] = '';
        $this->rs['dstudio_host_model_identifier'] = '';
        $this->rs['dstudio_host_new_network_location'] = '';
        $this->rs['dstudio_host_primary_key'] = '';
        $this->rs['dstudio_host_serial_number'] = '';
        $this->rs['dstudio_host_type'] = '';
        $this->rs['dstudio_hostname'] = '';
        $this->rs['dstudio_last_workflow'] = '';
        $this->rs['dstudio_last_workflow_duration'] = '';
        $this->rs['dstudio_last_workflow_execution_date'] = '';
        $this->rs['dstudio_last_workflow_status'] = '';
        $this->rs['dstudio_mac_addr'] = '';
        
        // Schema version, increment when creating a db migration
        $this->schema_version = 0;
        
        // Add indexes
        $this->idx[] = array('dstudio_host_serial_number');
        $this->idx[] = array('dstudio_hostname');
        $this->idx[] = array('dstudio_mac_addr');
        $this->idx[] = array('dstudio_last_workflow');
        $this->idx[] = array('cn');
        
        // Create table if it does not exist
        $this->create_table();
        
        if ($serial) {
            $this->retrieve_record($serial);
        }
        
        $this->serial_number = $serial;
        
        $this->module_dir = dirname(__FILE__);
    }

    // Override create_table to use illuminate/database capsule
    public function create_table() {
        // Check if we instantiated this table before
        if (isset($GLOBALS['tables'][$this->tablename])) {
            return true;
        }

        $capsule = $this->getCapsule();

        try {
            $exist = $capsule::table('deploystudio')->limit(1)->count();
        } catch (PDOException $e) {
            $capsule::schema()->create('deploystudio', function ($table) {
                $table->increments('id');
                $table->string('serial_number')->unique();
                $table->string('architecture');
                $table->string('cn');
                $table->string('dstudio_auto_disable');
                $table->string('dstudio_auto_reset_workflow');
                $table->string('dstudio_auto_started_workflow');
                $table->string('dstudio_bootcamp_windows_computer_name');
                $table->string('dstudio_disabled');
                $table->string('dstudio_group');
                $table->string('dstudio_host_ard_field_1');
                $table->string('dstudio_host_ard_field_2');
                $table->string('dstudio_host_ard_field_3');
                $table->string('dstudio_host_ard_field_4');
                $table->string('dstudio_host_ard_ignore_empty_fields');
                $table->string('dstudio_host_delete_other_locations');
                $table->string('dstudio_host_model_identifier');
                $table->string('dstudio_host_new_network_location');
                $table->string('dstudio_host_primary_key');
                $table->string('dstudio_host_serial_number');
                $table->string('dstudio_host_type');
                $table->string('dstudio_hostname');
                $table->string('dstudio_last_workflow');
                $table->string('dstudio_last_workflow_duration');
                $table->string('dstudio_last_workflow_execution_date');
                $table->string('dstudio_last_workflow_status');
                $table->string('dstudio_mac_addr');

                $table->index('cn', 'deploystudio_cn');
                $table->index('dstudio_host_serial_number', 'deploystudio_dstudio_host_serial_number');
                $table->index('dstudio_hostname', 'deploystudio_dstudio_hostname');
                $table->index('dstudio_last_workflow', 'deploystudio_dstudio_last_workflow');
                $table->index('dstudio_mac_addr', 'deploystudio_dstudio_mac_addr');
                $table->index('dstudio_host_serial_number', 'deploystudio_dstudio_host_serial_number');

            });

            // Store schema version in migration table
//            $migration = new Migration($this->tablename);
//            $migration->version = $this->schema_version;
//            $migration->save();

            alert("Created table '$this->tablename' version $this->schema_version");
        }

        // Store this table in the instantiated tables array
        $GLOBALS['tables'][$this->tablename] = $this->tablename;

        // Create table succeeded
        return true;
    }

     /**
     * Get DeployStudio data
     *
     * @return void
     * @author tuxudo (John Eberle)
     **/
    public function run_deploystudio_stats()
    {
        // Check if we should enable DeployStudio lookup
        if (conf('deploystudio_enable')) {
            // Load deploystudio helper
                require_once($this->module_dir.'/lib/deploystudio_helper.php');
                $ds_helper = new munkireport\module\deploystudio\Deploystudio_helper;
                $ds_helper->pull_deploystudio_data($this);

                // ^^ Comment and uncomment to turn off and on
        }
        
        return $this;
    }

    /**
     * Process method, is called by the client
     *
     * @return void
     * @author tuxudo (John Eberle)
     **/
    public function process()
    {
        $this->run_deploystudio_stats();
    }
}
