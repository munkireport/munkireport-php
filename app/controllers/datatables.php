<?php
class datatables extends Controller
{
	function __construct()
	{
		if( ! isset($_SESSION['user']))
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
			'sSearch' => '' // Search query
		);

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
		}

		// Add columns to config
		$cfg['cols'] = $cols;
		$cfg['sort_cols'] = $sortcols;

		//print_r($cfg);

		// Get model
		$obj = new Tablequery($cfg);

		//$obj->fetch($cfg);

		//return;

		echo json_encode($obj->fetch($cfg));

	}
	
}