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
            return;
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
        $obj = new View();
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
        }

        $queryobj = new Caching_model();
        
        // Check which version of data to return
        $sqlcheck = "SELECT startupstatus
                        FROM caching
                        WHERE serial_number = '$serial_number'";
            
        $caching_check = $queryobj->query($sqlcheck);  
                        
        if ( ! empty($caching_check[0]->startupstatus)) {
            // If column startupstatus is not empty/null, run SQL query for new dataset
            $sql = "SELECT ' ' AS 'groupdate', 
                            activated AS 'activated', 
                            active AS 'active', 
                            cachestatus AS 'cachestatus',
                            startupstatus AS 'startupstatus',
                            reachability AS 'reachability',
                            cachefree AS 'cachefree',
                            cacheused AS 'cacheused', 
                            cachelimit AS 'cachelimit', 
                            macsoftware AS 'macsoftware', 
                            appletvsoftware AS 'appletvsoftware', 
                            iossoftware AS 'iossoftware', 
                            iclouddata AS 'iclouddata', 
                            booksdata AS 'booksdata',
                            itunesudata AS 'itunesudata', 
                            moviesdata AS 'moviesdata', 
                            musicdata AS 'musicdata', 
                            otherdata AS 'otherdata', 
                            personalcachefree AS 'personalcachefree',
                            personalcacheused AS 'personalcacheused', 
                            personalcachelimit AS 'personalcachelimit',    
                            publicaddress AS 'publicaddress', 
                            privateaddresses AS 'privateaddresses',
                            port AS 'port', 
                            registrationstatus AS 'registrationstatus',
                            registrationerror AS 'registrationerror',
                            registrationresponsecode AS 'registrationresponsecode',
                            restrictedmedia AS 'restrictedmedia',
                            serverguid AS 'serverguid',
                            totalbytesreturnedtoclients AS 'totalbytesreturnedtoclients',
                            totalbytesreturnedtochildren AS 'totalbytesreturnedtochildren',
                            totalbytesreturnedtopeers AS 'totalbytesreturnedtopeers',
                            totalbytesstoredfromorigin AS 'totalbytesstoredfromorigin',
                            totalbytesstoredfromparents AS 'totalbytesstoredfromparents',
                            totalbytesstoredfrompeers AS 'totalbytesstoredfrompeers',
                            totalbytesdropped AS 'totalbytesdropped',
                            totalbytesimported AS 'totalbytesimported'
                            FROM caching 
                            WHERE serial_number = '$serial_number'";
        } else {
        
            // Else run legacy SQL query
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
        }
        
        $caching_tab = $queryobj->query($sql);
                
        $obj->view('json', array('msg' => current(array('msg' => $caching_tab)))); 
    }
    
    /**
     * REST API for retrieving caching data for chart
     * @tuxudo
     * 
     **/
    public function caching_graph()
    {
        $obj = new View();
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
        }
        
        $cachingdata = new Caching_model();

        switch ($cachingdata->get_driver()) {
            case 'sqlite':
                $sql = "SELECT DATE(collectiondateepoch, 'unixepoch') AS date,
                        sum(bytesfromcachetoclients) AS fromcache,
                        sum(bytesfromorigintoclients) AS fromorigin,
                        sum(bytespurgedtotal) AS purgedbytes
                        FROM caching
                        GROUP BY date
                        ORDER BY date";
                break;
            case 'mysql':
                $sql = "SELECT DATE(FROM_UNIXTIME(collectiondateepoch)) AS date, 
                        sum(bytesfromcachetoclients) AS fromcache,
                        sum(bytesfromorigintoclients) AS fromorigin,
                        sum(bytespurgedtotal) AS purgedbytes
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
        $purged = array();
                
        foreach ($cachingdata->query($sql) as $event) {
            $dates[] = $event->date;
            $cache[] = $event->fromcache;
            $origin[] = $event->fromorigin;
            $purged[] = $event->purgedbytes;
        }
        
        // Add in padded date to fix graph chop off
        $datetime = new DateTime('tomorrow');
        $dates[] =  $datetime->format('Y-m-d');
        $cache[] = 0;
        $origin[] = 0;
        $purged[] = 0;
        
        $numbervar = range(1,count($dates));
        
        $cacheassoc = array();
        foreach ($numbervar as $i => $value) {
            $cacheassoc[$value] = intval($cache[$i]);
        }
        
        $originassoc = array();
        foreach ($numbervar as $i => $value) {
            $originassoc[$value] = intval($origin[$i]);
        }
        
        $purgedassoc = array();
        foreach ($numbervar as $i => $value) {
            $purgedassoc[$value] = intval($purged[$i]);
        }
        
        $obj->view('json', array('msg' => array('dates' => $dates, 'cache' => $cacheassoc, 'origin' => $originassoc, 'purged' => $purgedassoc)));
    }
    
    
     /**
     * REST API for retrieving caching data for widget
     * @tuxudo
     *
     **/
     public function caching_widget()
     {        
        $obj = new View();
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
        }

        $queryobj = new Caching_model();
        
        $sql = "SELECT sum(bytesfromcachetoclients) AS fromcache,
                        sum(bytespurgedtotal) AS purgedbytes,
                        sum(bytesfromorigintoclients) AS fromorigin
                        FROM caching
                        LEFT JOIN reportdata USING (serial_number)
                        ".get_machine_group_filter();
        
        $caching_array = $queryobj->query($sql);
                
        $obj->view('json', array('msg' => current(array('msg' => $caching_array[0])))); 
     }
    
     /**
     * REST API for retrieving media caching data for media widget
     * @tuxudo
     *
     **/
     public function caching_media_widget()
     {        
        $obj = new View();
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
        }

        $queryobj = new Caching_model();
        
        $sql = "SELECT sum(booksdata) AS books,
                        sum(musicdata) AS music,
                        sum(moviesdata) AS movies
                        FROM caching
                        LEFT JOIN reportdata USING (serial_number)
                        ".get_machine_group_filter();
        
        $caching_array = $queryobj->query($sql);
                
        $obj->view('json', array('msg' => current(array('msg' => $caching_array[0])))); 
     }
    
     /**
     * REST API for retrieving software caching data for software widget
     * @tuxudo
     *
     **/
     public function caching_software_widget()
     {        
        $obj = new View();
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
        }

        $queryobj = new Caching_model();
        
        $sql = "SELECT sum(appletvsoftware) AS appletv,
                        sum(macsoftware) AS mac,
                        sum(iossoftware) AS ios
                        FROM caching
                        LEFT JOIN reportdata USING (serial_number)
                        ".get_machine_group_filter();
        
        $caching_array = $queryobj->query($sql);
                
        $obj->view('json', array('msg' => current(array('msg' => $caching_array[0])))); 
     }
    
     /**
     * REST API for retrieving icloud caching data for iCloud widget
     * @tuxudo
     *
     **/
     public function caching_icloud_widget()
     {        
        $obj = new View();
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
        }

        $queryobj = new Caching_model();
        
        $sql = "SELECT sum(iclouddata) AS icloud,
                        sum(itunesudata) AS itunesu,
                        sum(otherdata) AS other
                        FROM caching
                        LEFT JOIN reportdata USING (serial_number)
                        ".get_machine_group_filter();
        
        $caching_array = $queryobj->query($sql);
                
        $obj->view('json', array('msg' => current(array('msg' => $caching_array[0])))); 
     }
    
     /**
     * REST API for retrieving caching data for usage widget
     * @tuxudo
     *
     **/
     public function caching_usage_widget()
     {        
        $obj = new View();
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
        }

        $queryobj = new Caching_model();
        
        $sql = "SELECT SUM(CASE WHEN activated = '1' 
                        THEN cachefree 
                        ELSE 0 
                        END) AS cachefree,
                        SUM(CASE WHEN activated = '1' 
                        THEN cachelimit 
                        ELSE 0 
                        END) AS cachelimit,
                        SUM(CASE WHEN activated = '1' 
                        THEN cacheused 
                        ELSE 0 
                        END) AS cacheused
                        FROM caching
                        LEFT JOIN reportdata USING (serial_number)
                        ".get_machine_group_filter();
        
        $caching_array = $queryobj->query($sql);
                
        $obj->view('json', array('msg' => current(array('msg' => $caching_array[0])))); 
     }
    
    /**
     * Get reachability IP address for widget
     * @tuxudo
     *
     **/
    public function get_reachable_cache_name()
    {
        $obj = new View();
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }
        
        $cache = new Caching_model;
        $obj->view('json', array('msg' => $cache->get_reachable_cache_name()));
    }

} // END class Caching_controller
