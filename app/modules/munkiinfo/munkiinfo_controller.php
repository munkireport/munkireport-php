<?php
/**
 * munkiinfo status module class
 *
 * @package munkireport
 * @author
 **/
class munkiinfo_controller extends Module_controller
{
    
    protected $module_path;
    protected $view_path;


  /*** Protect methods with auth! ****/
    public function __construct()
    {
      // Store module path
        $this->module_path = dirname(__FILE__);
        $this->view_path = dirname(__FILE__) . '/views/';
    }
  /**
   * Default method
   *
   * @author
   **/
    public function index()
    {
        echo "You've loaded the munkiinfo module!";
    }
  
    /**
    * undocumented function summary
    *
    * Undocumented function long description
    *
    * @param type var Description
    * @return {11:return type}
    */
    public function listing($value = '')
    {
        if (! $this->authorized()) {
            redirect('auth/login');
        }
        $data['page'] = 'clients';
        $data['scripts'] = array("clients/client_list.js");
        $obj = new View();
        $obj->view('munkiprotocol_listing', $data, $this->view_path);
    }

  
  /**
   * Get Munki Protocol Statistics
   *
   * @author erikng
   **/
    public function get_protocol_stats()
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }

        $queryobj = new munkiinfo_model();
        $sql = "SELECT  COUNT(1) as total,
                      COUNT(CASE WHEN `munkiinfo_key` = 'munkiprotocol' AND `munkiinfo_value` = 'http' THEN 1 END) AS http,
                      COUNT(CASE WHEN `munkiinfo_key` = 'munkiprotocol' AND `munkiinfo_value` = 'https' THEN 1 END) AS https,
                      COUNT(CASE WHEN `munkiinfo_key` = 'munkiprotocol' AND `munkiinfo_value` = 'localrepo' THEN 1 END) AS localrepo
                       FROM munkiinfo
                       LEFT JOIN reportdata USING (serial_number)
                       ".get_machine_group_filter();
        $obj->view('json', array('msg' => current($queryobj->query($sql))));
    }

  /**
   * Get munki preferences for serial_number
   *
   * @param string $serial serial number
   * @author clburlison
   **/
    public function get_data($serial = '')
    {

        $out = array();
        $temp = array();
        if (! $this->authorized()) {
            $out['error'] = 'Not authorized';
        } else {
            $munkiinfo = new munkiinfo_model;
            foreach ($munkiinfo->retrieve_records($serial) as $prefs) {
                $temp[] = $prefs->rs;
            }
            foreach ($temp as $value) {
                $out[$value['munkiinfo_key']] = $value['munkiinfo_value'];
            }
        }

        $obj = new View();
        $obj->view('json', array('msg' => $out));
    }
} // END class default_module
