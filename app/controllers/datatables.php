<?php
class datatables extends Controller
{
	function __construct()
	{
		if( ! $this->authorized())
		{
			die('Authenticate first.'); // Todo: return json?
		}
	} 

	function data()
	{
		// Sanitize the GET variables here.
		$cfg = array(
			'iDisplayStart' => 0, // Start
			'iDisplayLength' => 0, // Length
			'sEcho' => 0, // Identifier, just return
			'iColumns' => 0, // Number of columns
			'iSortingCols' => 0, // Amount of sort columns
			'sSearch' => '', // Search query
			'mrColNotEmpty' => '', // Munkireport non empty column name
			'mrAddMachineTbl' => 1 // Add machine table
		);

		$sortcols = $searchcols = array();

		// Process $_GET array
		foreach($_GET as $k => $v)
		{
			if(isset($cfg[$k]))
			{
				$cfg[$k] = $v;
			}
			elseif(preg_match('/^mDataProp_(\d+)/', $k, $matches))
			{
				// Get colname from mDataProp_xx
				$cols[$matches[1]] = $v;
			}
			elseif(preg_match('/^iSortCol_(\d+)/', $k, $matches))
			{
				$col = $matches[1];
				if( ! isset($_GET["bSortable_$v"]))
					continue;

				if($_GET["bSortable_$v"] == "true")
                {
                    $sortcols[$v] = 'DESC';
                    if(isset($_GET["sSortDir_$col"]))
                    {
                    	if($_GET["sSortDir_$col"] === 'asc')
                    	{
                    		$sortcols[$v] = 'ASC';
                    	}
                    }
                }
            }
            elseif(preg_match('/^sSearch_(\d+)/', $k, $matches))
            {
            	$col = $matches[1];
				if( ! isset($_GET["bSearchable_$col"]))
					continue;

				if($_GET["bSearchable_$col"] == "true" && $v)
                {
                    $searchcols[$col] = $v;
                }
            }
		}

		// Add columns to config
		$cfg['cols'] = $cols;
		$cfg['sort_cols'] = $sortcols;
		$cfg['search_cols'] = $searchcols;

		//print_r($cfg);

		try
		{
			// Get model
			$obj = new Tablequery($cfg);
			echo json_encode($obj->fetch($cfg));
		}
		catch(Exception $e) 
		{
			echo json_encode(array(
				'error' => $e->getMessage(),
				'sEcho' => intval($cfg['sEcho'])
			));
		}

	}
	
}