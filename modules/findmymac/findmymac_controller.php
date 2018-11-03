<?php

/**
 * FindMyMac manifest status module class
 *
 * @package munkireport
 * @author poundbangbash/clburlison
 **/
class findmymac_controller extends Module_controller
{
    
    /*** Protect methods with auth! ****/
    public function __construct()
    {
        // Store module path
        $this->module_path = dirname(__FILE__);
    }

  /**
   * Get findmymac widget data
   *
   * @author clburlison
   **/
    public function get_stats()
    {
        $obj = new View();
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }

        $queryobj = new findmymac_model();
        $sql = "SELECT  COUNT(1) as total,
                      COUNT(CASE WHEN `status` = 'Enabled' THEN 1 END) AS Enabled,
                      COUNT(CASE WHEN `status` = 'Disabled' THEN 1 END) AS Disabled
                      FROM findmymac
                      LEFT JOIN reportdata USING (serial_number)
                      ".get_machine_group_filter();
        
        $obj->view('json', array('msg' => current($queryobj->query($sql))));
    }
    
  /**
 * Get findmymac information for serial_number
 *
 * @param string $serial serial number
 **/
    public function get_data($serial_number = '')
    {
          $obj = new View();
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }

          $findmymac = new findmymac_model($serial_number);
          $obj->view('json', array('msg' => $findmymac->rs));
    }
} // END class default_module
