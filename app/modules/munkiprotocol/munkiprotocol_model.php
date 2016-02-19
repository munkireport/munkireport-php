<?php
class munkiprotocol_model extends Model {

        function __construct($serial='')
        {
                parent::__construct('id', 'munkiprotocol'); //primary key, tablename
                $this->rs['id'] = '';
                $this->rs['serial_number'] = $serial; $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
                $this->rs['protocol_status'] = '';      

                // Schema version, increment when creating a db migration
                $this->schema_version = 2;
                
                // Add indexes
                $this->idx[] = array('protocol_status');

                // Create table if it does not exist
                $this->create_table();
                		
                if ($serial) {
                	$this->retrieve_record($serial);
                	
                $this->serial = $serial;
		}

        }

	/**
	 * Get Power statistics
	 *
	 *
	 **/
	public function get_stats()
	{
		$sql = "SELECT  COUNT(1) as total,
						COUNT(CASE WHEN protocol_status = 'http' THEN 1 END) AS http,
						COUNT(CASE WHEN protocol_status = 'https' THEN 1 END) AS https
			 			FROM munkiprotocol
			 			LEFT JOIN reportdata USING (serial_number)
			 			".get_machine_group_filter();
		return current($this->query($sql));

	}     

        // ------------------------------------------------------------------------

        /**
         * Process data sent by postflight
         *
         * @param string data
         * @author erikng
         **/
        function process($data)
        {               
                $this->protocol_status = $data;
                $this->save();
        }
}
