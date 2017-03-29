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
        // No authentication, the client needs to get here

        // Store module path
        $this->module_path = dirname(__FILE__);
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
     * Retrieve data in json format for client tab
     *
     * @return void
     * @author tuxudo
     **/
    public function get_client_tab_data($serial_number = '')
    {        
        if (! $this->authorized()) {
            die('Authenticate first.'); // Todo: return json
        }

        $queryobj = new Caching_model();
        
        $sql = "SELECT substr(collectiondate,1,10) AS 'groupdate', 
                        SUM(bytesfromcachetoclients) AS 'bytesfromcachetoclients', 
                        SUM(bytesfromorigintoclients) AS 'bytesfromorigintoclients', 
                        SUM(bytesfrompeerstoclients) AS 'bytesfrompeerstoclients', 
                        SUM(bytesfromcachetopeers) AS 'bytesfromcachetopeers', 
                        SUM(bytesfromorigintopeers) AS 'bytesfromorigintopeers', 
                        SUM(requestsfromclients) AS 'requestsfromclients', 
                        SUM(requestsfrompeers) AS 'requestsfrompeers',
                        SUM(bytespurgedyoungerthan1day) AS 'bytespurgedyoungerthan1day', 
                        SUM(bytespurgedyoungerthan7days) AS 'bytespurgedyoungerthan7days', 
                        SUM(bytespurgedyoungerthan30days) AS 'bytespurgedyoungerthan30days', 
                        SUM(bytespurgedtotal) AS 'bytespurgedtotal', 
                        SUM(bytesdropped) AS 'bytesdropped', 
                        SUM(repliesfromcachetoclients) AS 'repliesfromcachetoclients', 
                        SUM(repliesfromorigintoclients) AS 'repliesfromorigintoclients', 
                        SUM(repliesfrompeerstoclients) AS 'repliesfrompeerstoclients',
                        SUM(repliesfromcachetopeers) AS 'repliesfromcachetopeers',    
                        SUM(repliesfromorigintopeers) AS 'repliesfromorigintopeers', 
                        SUM(bytesimportedbyhttp) AS 'bytesimportedbyhttp', 
                        SUM(bytesimportedbyxpc) AS 'bytesimportedbyxpc', 
                        SUM(importsbyhttp) AS 'importsbyhttp',
                        SUM(importsbyxpc) AS 'importsbyxpc'
                        FROM caching 
                        WHERE serial_number = '$serial_number'
                        GROUP BY groupdate
                        ORDER BY groupdate DESC";
        
        $caching_tab = $queryobj->query($sql);
                
        $obj = new View();
        $obj->view('json', array('msg' => current(array('msg' => $caching_tab)))); 
    }
    
    /**
     * REST API for retrieving caching data for chart
     * @tuxudo
     * Don't know how to make the graph work :(
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
                        sum(bytesfromorigintoclients) AS fromorigin
                        FROM caching
                        GROUP BY date
                        ORDER BY date";
                break;
            case 'mysql':
                $sql = "SELECT DATE(FROM_UNIXTIME(collectiondateepoch)) AS date, 
                        sum(bytesfromcachetoclients) AS fromcache,
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
        $origin = array();
                
        foreach ($cachingdata->query($sql) as $event) {
            $dates[] = $event->date;
            $cache[] = $event->fromcache;
            $origin[] = $event->fromorigin;
        }
        
        $numbervar = range(1,count($dates));
        
        $cacheassoc = array();
        foreach ($numbervar as $i => $value) {
            $cacheassoc[$value] = intval($cache[$i]);
        }
        
        $originassoc = array();
        foreach ($numbervar as $i => $value) {
            $originassoc[$value] = intval($origin[$i]);
        }
        
        $out = array('cache' => $cacheassoc, 'origin' => $originassoc);
        
        $obj = new View();
        $obj->view('json', array('msg' => array('dates' => $dates, 'types' => $out)));
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
        
        $sql = "SELECT sum(bytesfromcachetoclients) AS fromcache,
                        sum(bytesfromorigintoclients) AS fromorigin,
                        sum(bytespurgedyoungerthan7days) AS purgedbytes
                        FROM caching
                        LEFT JOIN reportdata USING (serial_number)
                        ".get_machine_group_filter();
        
        $caching_array = $queryobj->query($sql);
                
        $obj = new View();
        $obj->view('json', array('msg' => current(array('msg' => $caching_array[0])))); 
    }

} // END class Caching_controller
