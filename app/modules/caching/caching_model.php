<?php
class Caching_model extends \Model
{
    public function __construct($serial = '')
    {
        parent::__construct('id', 'caching'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial; //$this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
        $this->rs['collectiondate'] = ""; // Date when data was written
        $this->rs['expirationdate'] = ""; // Date when data will expire
        $this->rs['collectiondateepoch'] = 0; // Date when data will expire
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

        // Schema version, increment when creating a db migration
        $this->schema_version = 0;
        
        //indexes to optimize queries
        $this->idx[] = array('serial_number');
        $this->idx[] = array('collectiondate');
        $this->idx[] = array('collectiondateepoch');
        $this->idx[] = array('bytesfromcachetoclients');
        $this->idx[] = array('bytesfrompeerstoclients');
        $this->idx[] = array('bytesfromorigintoclients');

        // Create table if it does not exist
       //$this->create_table();
    }

    // ------------------------------------------------------------------------
    /**
     * Process data sent by postflight
     *
     * @param string data
     *
     **/
    public function process($data)
    {
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
                
                $this->id = '';
                $this->create(); 
                $i=1;
            }
            }
        } //end foreach explode lines
    } // end process()
}
