<?php
class Caching_model extends \Model
{
    public function __construct($serial = '')
    {
        parent::__construct('id', 'caching'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial;
        $this->rs['collectiondate'] = ""; // Date when data was written
        $this->rs['expirationdate'] = ""; // Date when data will expire
        $this->rs['collectiondateepoch'] = 0; // Date when data was written, in UNIX time
        $this->rs['requestsfrompeers'] = 0; $this->rt['requestsfrompeers'] = 'BIGINT';
        $this->rs['requestsfromclients'] = 0; $this->rt['requestsfromclients'] = 'BIGINT';
        $this->rs['bytespurgedyoungerthan1day'] = 0; $this->rt['bytespurgedyoungerthan1day'] = 'BIGINT';
        $this->rs['bytespurgedyoungerthan7days'] = 0; $this->rt['bytespurgedyoungerthan7days'] = 'BIGINT';
        $this->rs['bytespurgedyoungerthan30days'] = 0; $this->rt['bytespurgedyoungerthan30days'] = 'BIGINT';
        $this->rs['bytespurgedtotal'] = 0; $this->rt['bytespurgedtotal'] = 'BIGINT';
        $this->rs['bytesfrompeerstoclients'] = 0; $this->rt['bytesfrompeerstoclients'] = 'BIGINT';
        $this->rs['bytesfromorigintopeers'] = 0; $this->rt['bytesfromorigintopeers'] = 'BIGINT';
        $this->rs['bytesfromorigintoclients'] = 0; $this->rt['bytesfromorigintoclients'] = 'BIGINT';
        $this->rs['bytesfromcachetopeers'] = 0; $this->rt['bytesfromcachetopeers'] = 'BIGINT';
        $this->rs['bytesfromcachetoclients'] = 0; $this->rt['bytesfromcachetoclients'] = 'BIGINT';
        $this->rs['bytesdropped'] = 0; $this->rt['bytesdropped'] = 'BIGINT';
        $this->rs['repliesfrompeerstoclients'] = 0; $this->rt['repliesfrompeerstoclients'] = 'BIGINT';
        $this->rs['repliesfromorigintopeers'] = 0; $this->rt['repliesfromorigintopeers'] = 'BIGINT';
        $this->rs['repliesfromorigintoclients'] = 0; $this->rt['repliesfromorigintoclients'] = 'BIGINT';
        $this->rs['repliesfromcachetopeers'] = 0; $this->rt['repliesfromcachetopeers'] = 'BIGINT';
        $this->rs['repliesfromcachetoclients'] = 0; $this->rt['repliesfromcachetoclients'] = 'BIGINT';
        $this->rs['bytesimportedbyxpc'] = 0; $this->rt['bytesimportedbyxpc'] = 'BIGINT';
        $this->rs['bytesimportedbyhttp'] = 0; $this->rt['bytesimportedbyhttp'] = 'BIGINT';
        $this->rs['importsbyxpc'] = 0; $this->rt['importsbyxpc'] = 'BIGINT';
        $this->rs['importsbyhttp'] = 0; $this->rt['importsbyhttp'] = 'BIGINT';
        $this->rs['activated'] = 0; // Start of High Sierra columns
        $this->rs['active'] = 0;
        $this->rs['cachestatus'] = "";
        $this->rs['appletvsoftware'] = 0; $this->rt['appletvsoftware'] = 'BIGINT';
        $this->rs['macsoftware'] = 0; $this->rt['macsoftware'] = 'BIGINT';
        $this->rs['iclouddata'] = 0; $this->rt['iclouddata'] = 'BIGINT';
        $this->rs['iossoftware'] = 0; $this->rt['iossoftware'] = 'BIGINT';
        $this->rs['booksdata'] = 0; $this->rt['booksdata'] = 'BIGINT';
        $this->rs['itunesudata'] = 0; $this->rt['itunesudata'] = 'BIGINT';
        $this->rs['moviesdata'] = 0; $this->rt['moviesdata'] = 'BIGINT';
        $this->rs['musicdata'] = 0; $this->rt['musicdata'] = 'BIGINT';
        $this->rs['otherdata'] = 0; $this->rt['otherdata'] = 'BIGINT';
        $this->rs['cachefree'] = 0; $this->rt['cachefree'] = 'BIGINT';
        $this->rs['cachelimit'] = 0; $this->rt['cachelimit'] = 'BIGINT';
        $this->rs['cacheused'] = 0; $this->rt['cacheused'] = 'BIGINT';
        $this->rs['personalcachefree'] = 0; $this->rt['personalcachefree'] = 'BIGINT';
        $this->rs['personalcachelimit'] = 0; $this->rt['personalcachelimit'] = 'BIGINT';
        $this->rs['personalcacheused'] = 0; $this->rt['personalcacheused'] = 'BIGINT';
        $this->rs['port'] = 0;
        $this->rs['publicaddress'] = ""; $this->rt['publicaddress'] = 'TEXT';
        $this->rs['privateaddresses'] = ""; $this->rt['privateaddresses'] = 'TEXT';
        $this->rs['registrationstatus'] = 0;
        $this->rs['registrationerror'] = "";
        $this->rs['registrationresponsecode'] = "";
        $this->rs['restrictedmedia'] = 0;
        $this->rs['serverguid'] = "";
        $this->rs['startupstatus'] = "";
        $this->rs['totalbytesdropped'] = 0; $this->rt['totalbytesdropped'] = 'BIGINT';
        $this->rs['totalbytesimported'] = 0; $this->rt['totalbytesimported'] = 'BIGINT';
        $this->rs['totalbytesreturnedtochildren'] = 0; $this->rt['totalbytesreturnedtochildren'] = 'BIGINT';
        $this->rs['totalbytesreturnedtoclients'] = 0; $this->rt['totalbytesreturnedtoclients'] = 'BIGINT';
        $this->rs['totalbytesreturnedtopeers'] = 0; $this->rt['totalbytesreturnedtopeers'] = 'BIGINT';
        $this->rs['totalbytesstoredfromorigin'] = 0; $this->rt['totalbytesstoredfromorigin'] = 'BIGINT';
        $this->rs['totalbytesstoredfromparents'] = 0; $this->rt['totalbytesstoredfromparents'] = 'BIGINT';
        $this->rs['totalbytesstoredfrompeers'] = 0;  $this->rt['totalbytesstoredfrompeers'] = 'BIGINT';
        $this->rs['reachability'] = "";  $this->rt['reachability'] = 'TEXT'; // end of High Sierra Columns

        // Schema version, increment when creating a db migration
        $this->schema_version = 1;
        
        //indexes to optimize queries
        $this->idx[] = array('serial_number');
        $this->idx[] = array('collectiondate');
        $this->idx[] = array('collectiondateepoch');
        $this->idx[] = array('bytesfromcachetoclients');
        $this->idx[] = array('bytesfrompeerstoclients');
        $this->idx[] = array('bytesfromorigintoclients');
        $this->idx[] = array('activated');
        $this->idx[] = array('active');
        $this->idx[] = array('cachestatus');
        $this->idx[] = array('totalbytesreturnedtoclients');
        $this->idx[] = array('totalbytesreturnedtochildren');
        $this->idx[] = array('totalbytesreturnedtopeers');
        $this->idx[] = array('totalbytesstoredfromorigin');
        $this->idx[] = array('totalbytesstoredfromparents');
        $this->idx[] = array('totalbytesstoredfrompeers');

        // Create table if it does not exist
       //$this->create_table();
    }

    // ------------------------------------------------------------------------
    
    /**
     * Get reability IP address for widget
     *
     **/
    public function get_reachable_cache_name()
    {
        $sql = "SELECT COUNT(CASE WHEN reachability <> '' AND reachability IS NOT NULL THEN 1 END) AS count, reachability 
                FROM caching
                LEFT JOIN reportdata USING (serial_number)
                ".get_machine_group_filter()."
                GROUP BY reachability
                ORDER BY count DESC";
        
        foreach ($this->query($sql) as $obj) {
            if ("$obj->count" !== "0") {
                $obj->reachability = $obj->reachability ? $obj->reachability : 'Unknown';
                $out[] = $obj;
            }
        }
        
        return $out;
    }
    
    /**
     * Process data sent by postflight
     *
     * @param string data
	 * @author tuxudo
     **/
    public function process($data)
    {
        // If data is empty, throw error
        if (! $data) {
            //throw new Exception("Error Processing Caching Module Request: No data found", 1);
        } else if (substr( $data, 0, 26 ) != '[{"name":"status","result"' ) { // Else if old style text, process with old text based handler
            
            // Delete previous entries
            $this->deleteWhere('serial_number=?', $this->serial_number);

            $cache_array = array();
            $i=1;
            $c=21;

            // Parse data
            foreach(explode("\n", $data) as $line) {
                $cache_line = explode("|", $line);

                if (! empty($line)) {
                      $cache_array[(str_replace(".", "", $cache_line[3]))] = $cache_line[4]; 
                      $i++;
                    
                    if ( $i == 22 ) {

                          $dt = new DateTime("@$cache_line[1]");
                          $cache_array['collectiondate'] = ($dt->format('Y-m-d H:i:s'));
                          $dt = new DateTime("@$cache_line[2]");
                          $cache_array['expirationdate'] = ($dt->format('Y-m-d H:i:s')); 
                          $cache_array['collectiondateepoch'] = $cache_line[1]; 

                          foreach($cache_array as $cache_item => $item) {
                            $this->$cache_item = $cache_array[$cache_item]; 
                          }

                        // Save the entry
                        $this->id = '';
                        $this->create(); 
                        $i=1;
                    }
                }
            } // End foreach explode lines
        } else { // Process data with new, fancy pants JSON handler
            
            // Delete previous entries, bye bye data
            $this->deleteWhere('serial_number=?', $this->serial_number);
                    
            // Process JSON into PHP object thingy
            $cachingjson = json_decode($data, true);
            
            // Translate caching object strings to db fields
            $translate = array(
                'Activated' => 'activated',
                'Active' => 'active',
                'CacheStatus' => 'cachestatus',
                'CacheFree' => 'cachefree',
                'CacheLimit' => 'cachelimit',
                'CacheUsed' => 'cacheused',
                'CacheDetails' => 'cachedetails',
                'PersonalCacheFree' => 'personalcachefree',
                'PersonalCacheLimit' => 'personalcachelimit',
                'PersonalCacheUsed' => 'personalcacheused',
                'Port' => 'port',
                'PublicAddress' => 'publicaddress',
                'PrivateAddresses' => 'privateaddresses',
                'reachability' => 'reachability',
                'RegistrationStatus' => 'registrationstatus',
                'RegistrationError' => 'registrationerror',
                'RegistrationResponseCode' => 'registrationresponsecode',
                'RestrictedMedia' => 'restrictedmedia',
                'ServerGUID' => 'serverguid',
                'StartupStatus' => 'startupstatus',
                'TotalBytesDropped' => 'totalbytesdropped',
                'TotalBytesImported' => 'totalbytesimported',
                'TotalBytesReturnedToChildren' => 'totalbytesreturnedtochildren',
                'TotalBytesReturnedToClients' => 'totalbytesreturnedtoclients',
                'TotalBytesReturnedToPeers' => 'totalbytesreturnedtopeers',
                'TotalBytesStoredFromOrigin' => 'totalbytesstoredfromorigin',
                'TotalBytesStoredFromParents' => 'totalbytesstoredfromparents',
                'TotalBytesStoredFromPeers' => 'totalbytesstoredfrompeers'
            );
            
            $cachedetailstranslate = array (
                'Apple TV Software' => 'appletvsoftware',
                'Mac Software' => 'macsoftware',
                'iCloud' => 'iclouddata',
                'iOS Software' => 'iossoftware',
                'Books' => 'booksdata',
                'iTunes U' => 'itunesudata',
                'Movies' => 'moviesdata',
                'Music' => 'musicdata',
                'Other' => 'otherdata',
            );
            
            $booleans = array('activated','active','registrationstatus','restrictedmedia');
        
            $nestedarrays = array('cachedetails');
                        
            // Traverse the caching object with translations
            foreach ($translate as $search => $field) { 

                if (! array_key_exists($search, $cachingjson[0]["result"])){
                    // Skip keys that may not exist and null the value
                    $this->$field = '';
                                
                    // Format booleans before processing
                } else if (in_array($field, $booleans) && ($cachingjson[0]["result"][$search] == "true" || $cachingjson[0]["result"][$search] == "1")) {
                    // Send a 1 to the db
                    $this->$field = '1';
                    
                } else if (in_array($field, $booleans) && ($cachingjson[0]["result"][$search] == "false" || $cachingjson[0]["result"][$search] == "0")) {
                    // Send a 0 to the db
                    $this->$field = '0';

                } else if (! empty($cachingjson[0]["result"][$search]) && ! is_array($cachingjson[0]["result"][$search])) { 
                    // If key is not empty, save it to the object
                    $this->$field = $cachingjson[0]["result"][$search];
                    
                } else if (is_array($cachingjson[0]["result"][$search]) && ! in_array($field, $nestedarrays) && ! empty($cachingjson[0]["result"][$search])){
                    // If is an array and not a nested array, and is not empty, condense it to a string and save it
                    $this->$field = implode(", ", $cachingjson[0]["result"][$search]);                    
                    
                } else if ($search == "CacheDetails" && ! empty($cachingjson[0]["result"][$search])){                    
                    // Fill out the caching details values from the CacheDetails array
                    
                    foreach ($cachedetailstranslate as $detailssearch => $detailsfield){
                        if (! empty($cachingjson[0]["result"][$search][$detailssearch])) {
                            // If detail search isn't empty, save value
                            $this->$detailsfield = $cachingjson[0]["result"][$search][$detailssearch];
                        } else {
                            // Else, set value to zero
                            $this->$detailsfield = "0";
                        }
                    }
                    
                } else if ($cachingjson[0]["result"][$search] == "0" && ! is_array($cachingjson[0]["result"][$search])){
                    // Set the value to 0 if it's 0                        
                    $this->$field = "0";
                } else {  
                    // Else, null the value
                    $this->$field = '';
                }
            }
            
            // Save it, like that cake you just dropped onto the floor after pulling it out of the oven. Yea, I saw that
            $this->id = '';
            $this->create(); 
        }
    } // End process()
}
