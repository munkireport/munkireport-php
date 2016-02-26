<?php
class munkiinfo_model extends Model {

	  function __construct($serial='')
	  {
	          parent::__construct('id', 'munkiinfo'); //primary key, tablename
	          $this->rs['id'] = 0;
	          $this->rs['serial_number'] = $serial;
						$this->rs['key'] = '';
						$this->rs['value'] = '';

						// Schema version, increment when creating a db migration
	          $this->schema_version = 0;

	          // Add indexes
	          $this->idx[] = array('serial_number');

	          // Create table if it does not exist
	          $this->create_table();

	          if ($serial) {
			$this->retrieve_record($serial);

	          $this->serial = $serial;
	          }

	  }

		/**
		 * Get protocol statistics
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
  function process($plist)
  {
      require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');
      $parser = new CFPropertyList();
      $parser->parse($plist);

      $plist = $parser->toArray();

      $this->delete_where('serial_number=?', $this->serial_number);
			$item = array_pop($plist);

			reset($item);
			while (list($key, $val) = each($item)) {
					$this->key = $key;
					$this->value = $val;

					$this->id = '';
					$this->save();

			}
  }

	/**
	 * Return all preferences items for the given serial number
	 */
	public function itemsBySerialNumber($aSerialNumber)
	{
		return $this->retrieve_records($aSerialNumber);
	}
}
