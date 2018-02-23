<?php
class backup2go_model extends \Model {
    
    protected $restricted;
    
	/**
	 * function: __construct
	 * initialise module
	 **/
	function __construct($serial='')
	{
		parent::__construct('id', 'backup2go'); //primary key, tablename

		//initialise tables
		$this->rs['id'] = 0;
		$this->rs['serial_number'] = $serial;
		$this->rs['backupdate'] = '';
        
    if ($serial)
    {
        $this->retrieve_record($serial);
    }

    $this->serial = $serial;
	}

	/**
	 * function: process($data)
	 * data sends by postflight
	 * processes the data returned in the postflight
	 **/
	function process($data)
	{
		//init variables
		$column = "backupdate";
		$last = "";

		//check if data is empty
		if($data != ""){
			$arr_data = explode("\n", $data);
			
			//loop over the lines, but we only need the last line (cause values append at the end)
			foreach($arr_data as $line){
				if($line != ""){
					$last = $line;
				}
			}

			//save last date
			$this->$column = $last;
			$this->save();
		}
	}

	/**
	 * function: get_stats
	 * this script is used for the widget in the report page
	 * it returns label classes and values for the widget
	 **/
	function get_stats($hours)
	{
		$now = time();
		$today = $now - 3600 * 24;
		$two_weeks_ago = $now - 3600 * 24 * 14;
		$month_ago = $now - 3600 * 24 * 28;
		$sql = "SELECT COUNT(1) as total, 
			COUNT(CASE WHEN backupdate > '$two_weeks_ago' THEN 1 END) AS b2g_ok,
			COUNT(CASE WHEN backupdate > '$month_ago' AND backupdate < '$two_weeks_ago'  THEN 1 END) AS b2g_warning,
			COUNT(CASE WHEN backupdate < '$month_ago'  THEN 1 END) AS b2g_danger

			FROM backup2go
			LEFT JOIN reportdata USING (serial_number)
			".get_machine_group_filter();
		return current($this->query($sql));
	}
}
