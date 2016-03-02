<?php
/**
 * munkiinfo status module class
 *
 * @package munkireport
 * @author
 **/
class munkiinfo_controller extends Module_controller
{

  /*** Protect methods with auth! ****/
  function __construct()
  {
    // Store module path
    $this->module_path = dirname(__FILE__);
  }
  /**
   * Default method
   *
   * @author
   **/
  function index()
  {
    echo "You've loaded the munkiinfo module!";
  }
  
  /**
   * Get Munki Protocol Statistics
   *
   * @author erikng
   **/
  function get_protocol_stats()
  {

      if( ! $this->authorized())
      {
          // die('Authenticate first.'); // Todo: return json
          $out['error'] = 'Not authorized';
      }

      $queryobj = new munkiinfo_model();
      $sql = "SELECT  COUNT(1) as total,
                      COUNT(CASE WHEN `munkiinfo_key` = 'munkiprotocol' AND `munkiinfo_value` = 'http' THEN 1 END) AS http,
                      COUNT(CASE WHEN `munkiinfo_key` = 'munkiprotocol' AND `munkiinfo_value` = 'https' THEN 1 END) AS https,
                      COUNT(CASE WHEN `munkiinfo_key` = 'munkiprotocol' AND `munkiinfo_value` = 'localrepo' THEN 1 END) AS localrepo
                       FROM munkiinfo
                       LEFT JOIN reportdata USING (serial_number)
                       ".get_machine_group_filter();
      $obj = new View();
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
    if( ! $this->authorized())
    {
      $out['error'] = 'Not authorized';
    }
    else
    {
      $munkiinfo = new munkiinfo_model;
      foreach($munkiinfo->retrieve_records($serial) as $prefs)
      {
        $temp[] = $prefs->rs;
      }
      foreach($temp as $value)
      {
        $out[$value['munkiinfo_key']] = $value['munkiinfo_value'];
      }
    }

    $obj = new View();
    $obj->view('json', array('msg' => $out));
  }

} // END class default_module
