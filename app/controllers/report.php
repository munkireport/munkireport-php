<?php
class report extends Controller
{
    function hash_check()
	{
		// Check if we have a serial and data
		if( ! isset($_POST['serial'])) die('Serial is missing');
		if( ! isset($_POST['items'])) die('Items are missing');

		// Register check in reportdata
		$report = new Reportdata($_POST['serial']);
		$report->register()->save();

		$req_items = unserialize($_POST['items']); //Todo: check if array

		$itemarr = array();

		// Get stored hashes from db
		$hash = new Hash();
		$hashes = $hash->all($_POST['serial']);

		// Compare sent hashes with stored hashes
		foreach($req_items as $key => $val)
		{
		    if( ! (isset($hashes[$key]) && $hashes[$key] == $val['hash']))
		    {
		        $itemarr[$key] = 1;
		    }
		}
		
		// Return list of changed hashes
		echo serialize($itemarr);
	}
	
	function check_in()
	{
	    if( ! isset($_POST['items']))
	    {
	    	$this->msg("No items in POST", TRUE);
	    }

		try {
			$arr = unserialize($_POST['items']);
		} catch (Exception $e) {
			$this->msg("Could not parse items, not a proper serialized file", TRUE);
		}
		
		foreach($arr as $key => $val)
		{
			// Skip items without data
		    if ( ! isset($val['data'])) continue;

		   	$this->msg("Starting: $key");

		   	// Preserve classname
		   	$classname = $key;

		   	// All models are lowercase
		   	$key = strtolower($key);
			
			// Check if this is a module-model
			if(substr($key, -6) == '_model')
			{
				$module = substr($key, 0, -6);
				$model_path = APP_PATH."modules/${module}/";
			}
			else
			{
				$model_path = APP_PATH . 'models/';
			}
		    
		    // Todo: prevent admin and user models, sanitize $key
		    if( ! file_exists($model_path . $key . '.php'))
		    {
		    	$this->msg("Model not found: $key");
		    	continue;
		    }
		    require_once($model_path . $key . '.php');

		    if ( ! class_exists( $classname, false ) )
		    {
		    	$this->msg("Class not found: $classname");
		    	continue;
		    }

		   	// Load model
	        $class = new $classname($_POST['serial']);

        	
	        if( ! method_exists($class, 'process'))
	        {
	        	$this->msg("No process method in: $classname");
		    	continue;
	        }

	       	try {
				
				$class->process($val['data']);
		
		        // Store hash
		        $hash = new Hash($_POST['serial'], $key);
		        $hash->hash = $val['hash'];
				$hash->timestamp = time();
		        $hash->save();
	       		
	       	} catch (Exception $e) {
	       		$this->msg("An error occurred while processing: $classname");
	       		
	       	}
	        		
		}
	}

	/**
	 *
	 * @param string message
	 * @param boolean exit or not
	 * @author AvB
	 **/
	function msg($msg = 'No message', $exit = FALSE)
	{
		echo('Server: '.$msg."\n");
		if($exit)
		{
			exit();
		}
	}

	
}
