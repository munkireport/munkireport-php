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

        if ($serial) {
            $this->retrieve_record($serial);
        }
        
        $this->serial_number = $serial;
        
        $this->module_dir = dirname(__FILE__);
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
