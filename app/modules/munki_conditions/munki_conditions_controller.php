<?php
/**
 * munki_conditions status module class
 *
 * @package munkireport
 * @author nperkins487
 **/
class munki_conditions_controller extends Module_controller
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
        echo "You've loaded the munki_conditions module!";
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
            $munki_conditions = new munki_conditions_model;
            foreach ($munki_conditions->retrieve_records($serial) as $conditions) {
                $temp[] = $conditions->rs;
            }
            foreach ($temp as $value) {
                $out[$value['munkiinfo_key']] = $value['munkiinfo_value'];
            }
        }

        $obj = new View();
        $obj->view('json', array('msg' => $out));
    }

} // END class default_module
