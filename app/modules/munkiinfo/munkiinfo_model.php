<?php
class munkiinfo_model extends Model {

        function __construct($serial='')
        {
                parent::__construct('id', 'munkiinfo'); //primary key, tablename
                $this->rs['id'] = '';
                $this->rs['serial_number'] = $serial; $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
                $this->rs['munkiprotocol'] = '';
                //$this->rs['runstate'] = 'done';
                //$this->rs['runtype'] = '';
                //$this->rs['starttime'] = '';
                //$this->rs['endtime'] = '';
                //$this->rs['version'] = '';

                // Schema version, increment when creating a db migration
                $this->schema_version = 2;

                // Add indexes
                $this->idx[] = array('munkiprotocol');

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
	public function get_protocol_stats()
	{
		$sql = "SELECT  COUNT(1) as total,
						COUNT(CASE WHEN munkiprotocol = 'http' THEN 1 END) AS http,
						COUNT(CASE WHEN munkiprotocol = 'https' THEN 1 END) AS https,
						COUNT(CASE WHEN `munkiprotocol` = 'localrepo' THEN 1 END) AS localrepo
						FROM munkiinfo
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
                $this->munkiprotocol = $data;
                $this->save();
        }
}
