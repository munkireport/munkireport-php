<?php

/**
 * Caching_controller class
 *
 * @package munkireport
 * @author @tuxudo
 **/
class Caching_controller extends Module_controller
{
    public function __construct()
    {
        //$this->module_path = dirname(__FILE__) .'/';
        //$this->view_path = $this->module_path . 'views/';
         if (! $this->authorized()) {
            die('Authenticate first.'); // Todo: return json?
        }

        header('Access-Control-Allow-Origin: *');
    }

    /**
     * Default method
     *
     * @author AvB
     **/
    public function index()
    {
        echo "You've loaded the caching report module!";
    }

    /**
     * Retrieve data in json format
     *
     * @return void
     * @author tuxudo
     **/
    public function get_data($serial_number = '')
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
        }

        $caching = new Caching_model;
        $obj->view('json', array('msg' => $caching->retrieve_records($serial_number)));
    }
    
    
    /**
     * REST API for retrieving caching data for chart
     * @tuxudo
     * Chart widget/client tab doesn't exist because I couldn't get it to work :/
     * But it returns pretty data comprising of date, from cache, from peers, and from origin :D
     *
     **/
    public function caching_graph()
    {
        if (! $this->authorized()) {
            die('Authenticate first.');
        }
        
        $cachingdata = new Caching_model();

        switch ($cachingdata->get_driver()) {
            case 'sqlite':
                $sql = "SELECT DATE(collectiondateepoch, 'unixepoch') AS date,
						sum(bytesfromcachetoclients) AS fromcache,
						sum(bytesfrompeerstoclients) AS frompeers,
						sum(bytesfromorigintoclients) AS fromorigin
						FROM caching
                        GROUP BY date
						ORDER BY date";
                break;
            case 'mysql':
                $sql = "SELECT DATE(FROM_UNIXTIME(collectiondateepoch)) AS date, 
						sum(bytesfromcachetoclients) AS fromcache,
						sum(bytesfrompeerstoclients) AS frompeers,
						sum(bytesfromorigintoclients) AS fromorigin
						FROM caching
                        GROUP BY date
						ORDER BY date";
                break;
            default:
                die('Unknown database driver');
        }
        $dates = array();
        $cache = array();
        $peers = array();
        $origin = array();
                
        foreach ($cachingdata->query($sql) as $event) {
            $dates[] = $event->date;
            $cache[] = $event->fromcache;
            $peers[] = $event->frompeers;
            $origin[] = $event->fromorigin;
        }

        $obj = new View();
        $obj->view('json', array('msg' => array('dates' => $dates, 'cache' => $cache, 'peers' => $peers, 'origin' => $origin)));
    }
    
    
     /**
     * REST API for retrieving caching data for widget
     * @tuxudo
     *
     **/
    public function caching_widget()
    {        
        if (! $this->authorized()) {
            die('Authenticate first.'); // Todo: return json
        }

        $queryobj = new Caching_model();
        
        $sql = "SELECT 	round((sum(bytesfromcachetoclients)/1024/1024.0),4) AS fromcache,
						round((sum(bytesfrompeerstoclients)/1024/1024.0),4) AS frompeers,
						round((sum(bytesfromorigintoclients)/1024/1024.0),4) AS fromorigin
						FROM caching
                        LEFT JOIN reportdata USING (serial_number)
                        ".get_machine_group_filter();
        
        $caching_array = $queryobj->query($sql);
            
        if ( $caching_array[0]->fromcache <= 1 ) {  //format fromcache
            $caching_array[0]->fromcache = (round($caching_array[0]->fromcache * 1024, 2))."KB";
        } elseif ( $caching_array[0]->fromcache <= 1023 ) {  
            $caching_array[0]->fromcache = (round($caching_array[0]->fromcache, 2))."MB";
        } elseif ( $caching_array[0]->fromcache <= 1048575 ) {  
            $caching_array[0]->fromcache = (round($caching_array[0]->fromcache / 1024, 2))."GB";
        }elseif ( $caching_array[0]->fromcache >= 1048576 ) {  
            $caching_array[0]->fromcache = (round($caching_array[0]->fromcache / 1024 / 1024, 2))."TB";
        }
             
        //if ( $caching_array[0]->frompeers <= 1 ) {  //format frompeers
        //    $caching_array[0]->frompeers = (round($caching_array[0]->frompeers * 1024, 2))."KB";
        //} elseif ( $caching_array[0]->frompeers <= 1023 ) {  
        //    $caching_array[0]->frompeers = (round($caching_array[0]->frompeers, 2))."MB";
        //} elseif ( $caching_array[0]->frompeers <= 1048575 ) {  
        //    $caching_array[0]->frompeers = (round($caching_array[0]->frompeers / 1024, 2))."GB";
        //}elseif ( $caching_array[0]->frompeers >= 1048576 ) {  
        //    $caching_array[0]->frompeers = (round($caching_array[0]->frompeers / 1024 / 1024, 2))."TB";
        //}
        // Peers is turned off because it isn't used in the widget
        
        if ( $caching_array[0]->fromorigin <= 1 ) {  //format fromorigin
            $caching_array[0]->fromorigin = (round($caching_array[0]->fromorigin * 1024, 2))."KB";
        } elseif ( $caching_array[0]->fromorigin <= 1023 ) {  
            $caching_array[0]->fromorigin = (round($caching_array[0]->fromorigin, 2))."MB";
        } elseif ( $caching_array[0]->fromorigin <= 1048575 ) {  
            $caching_array[0]->fromorigin = (round($caching_array[0]->fromorigin / 1024, 2))."GB";
        }elseif ( $caching_array[0]->fromorigin >= 1048576 ) {  
            $caching_array[0]->fromorigin = (round($caching_array[0]->fromorigin / 1024 / 1024, 2))."TB";
        }
                
        $obj = new View();
        $obj->view('json', array('msg' => current(array('msg' => $caching_array[0])))); 
    }

} // END class Caching_controller