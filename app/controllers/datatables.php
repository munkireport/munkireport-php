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
			'columns' => array(),
			'order' => array(),
			'start' => 0, // Start
			'length' => 0, // Length
			'draw' => 0, // Identifier, just return
			'search' => '', // Search query
			'where' => '', // Optional where clause
			'mrColNotEmpty' => '' // Munkireport non empty column name
		);
		//echo '<pre>';print_r($_GET);return;

		$searchcols = array();

		// Process $_POST array
		foreach($_POST as $k => $v)
		{
			if($k == 'search')
			{
				$cfg['search'] = $v['value'];
			}
			elseif(isset($cfg[$k]))
			{
				$cfg[$k] = $v;
			}
		}// endforeach

		// Add columns to config
		$cfg['search_cols'] = $searchcols;

		//echo '<pre>';print_r($cfg);

		try
		{
			// Get model
			$obj = new Tablequery($cfg);
			//echo '<pre>';print_r($obj->fetch($cfg));
			echo json_encode($obj->fetch($cfg));
			//echo json_last_error_msg();
		}
		catch(Exception $e) 
		{
			echo json_encode(array(
				'error' => $e->getMessage(),
				'draw' => intval($cfg['draw'])
			));
		}

	}
	
}