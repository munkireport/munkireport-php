<?php

/**
 * managedinstalls class
 *
 * @package munkireport
 * @author
 **/
class managedinstalls_controller extends Module_controller
{
    
    protected $module_path;
    protected $view_path;
    
    /*** Protect methods with auth! ****/
    function __construct()
    {
        // Store module path
        $this->module_path = dirname(__FILE__);
        $this->view_path = dirname(__FILE__) . '/views/';
    }
    
      /**
     * Get managedinstalls information for serial_number
     *
     * @param string $serial serial number
     **/
    public function get_data($serial_number = '')
    {
        $out = array();
        if (! $this->authorized()) {
            $out['error'] = 'Not authorized';
        } else {
            $model = new Managedinstalls_model;
            foreach ($model->retrieve_records($serial_number) as $prefs) {
                $out[] = $prefs->rs;
            }
        }

        $obj = new View();
        $obj->view('json', array('msg' => $out));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get pending installs
     *
     *
     * @param string $type Type, munki or apple
     **/
    public function get_pending_installs($type = "munki")
    {
        $out = array();
        if (! $this->authorized()) {
            $out['error'] = 'Not authorized';
        } else {
            $model = new Managedinstalls_model;
            $hoursBack = 24 * 7; // Hours back
            $out = array();
            
            foreach ($model->get_pending_installs($type, $hoursBack) as $obj) {
                
                $out[] = $obj;
            
            }
    
        }
        
        $obj = new View();
        $obj->view('json', array('msg' => $out));

    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get package statistics
     *
     * Get statistics about a packat
     *
     * @param string name Package name
     * @return {11:return type}
     */
    public function get_pkg_stats($pkg = '')
    {
        $out = array();
        if (! $this->authorized()) {
            $out['error'] = 'Not authorized';
        } else {
            $model = new Managedinstalls_model;
            
            // Convert to list
            foreach ($model->get_pkg_stats($pkg) as $rs) {
                $status = $rs->status == 'install_succeeded' ? 'installed' : $rs->status;
                $key = $rs->name . $rs->version;
                if (isset($out[$key])) {
                    
                    if (isset($out[$key][$status])) {
                        // $key exists, add count
                        $out[$key][$status] += $rs->count;
                    } else {
                        $out[$key][$status] = $rs->count;
                    }
                } else {
                    $out[$key] = array(
                        'name' => $rs->name,
                        'version' => $rs->version,
                        'display_name' => $rs->display_name,
                        $status => $rs->count,
                    );
                }
            }
        }

        $obj = new View();
        $obj->view('json', array('msg' => array_values($out)));

    }

    
    /**
     * Get installs statistics
     *
     * Undocumented function long description
     *
     * @param int $hours number of hours back or 0 for all
     * @return {11:return type}
     */
    public function get_stats($hours = 0)
    {
        $out = array();
        if (! $this->authorized()) {
            $out['error'] = 'Not authorized';
        } else {
            $model = new Managedinstalls_model;
            foreach ($model->get_stats($hours) as $rs) {
                $out[] = $rs;
            }
        }

        $obj = new View();
        $obj->view('json', array('msg' => $out));

    }
    
    /**
     * undocumented function summary
     *
     * Undocumented function long description
     *
     * @param type var Description
     * @return {11:return type}
     */
    public function listing($name = '', $version = '')
    {
        if (! $this->authorized()) {
            redirect('auth/login');
        }
        $data['name'] = rawurldecode($name);
        $data['version'] = rawurldecode($version);
        $data['page'] = 'clients';
        $data['scripts'] = array("clients/client_list.js");
        $obj = new View();
        $obj->view('managed_installs_list', $data, $this->view_path);
    }
    
    /**
     * View a file
     * TODO: move to parent?
     * @param string $page filename
     */
    public function view($page)
    {
        if (! $this->authorized()) {
            redirect('auth/login');
        }
        
        $obj = new View();
        $obj->view($page, '', $this->view_path);
    }
    
    /**
     * Get machines with $status installs
     *   *
     * @param integer $hours Number of hours to get stats from
     **/
    public function get_clients($status = 'pending_install', $hours = 24)
    {
        $out = array();
        if (! $this->authorized()) {
            $out['error'] = 'Not authorized';
        } else {
            $model = new Managedinstalls_model;
            foreach ($model->get_clients($status, $hours) as $rs) {
                $out[] = $rs;
            }
        }
        
        $obj = new View();
        $obj->view('json', array('msg' => $out));

    }
} // END class default_module
