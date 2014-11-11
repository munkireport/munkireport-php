<?php
class report extends Controller
{
    /**
     * Constructor: test if authentication is needed
     * and check if the client has the proper credentials
     *
     * @author AvB
     **/
    function __construct()
	{
		if ($auth_list = conf('client_passphrases'))
		{
			if ( ! is_array($auth_list))
			{
				$this->error("conf['client_passphrases'] should be an array");
			}

			if ( ! isset($_POST['passphrase']))
			{
				$this->error("passphrase is required but missing");
			}

			if ( ! in_array($_POST['passphrase'], $auth_list))
			{
				$this->error('passphrase "'.$_POST['passphrase'].'" not accepted');
			}
		}
	} 

	/**
	 * Hash check script for clients
	 *
	 * Clients check in hashes using $_POST
	 * This script returns a JSON array with
	 * hashes that are different
	 *
	 * @author AvB
	 **/    
	function hash_check()
	{
		// Check if we have a serial and data
		if ( ! isset($_POST['serial']))
		{
			$this->error("Serial is missing");
		}

		if ( ! isset($_POST['items']))
		{
			$this->error("Items are missing");
		}

		$itemarr = array('error' => '');

		// Try to register client and lookup hashes in db
		try
		{
			// Register check in reportdata
			$report = new Reportdata_model($_POST['serial']);
			$report->register()->save();

			$req_items = unserialize($_POST['items']); //Todo: check if array

			// Get stored hashes from db
			$hash = new Hash();
			$hashes = $hash->all($_POST['serial']);

			// Compare sent hashes with stored hashes
			foreach($req_items as $name => $val)
			{
			    
			    // All models are lowercase
			    $lkey = strtolower($name);

			    // Rename legacy InventoryItem to inventory
				$lkey = str_replace('inventoryitem', 'inventory', $lkey);

			    // Remove _model legacy
			    if(substr($lkey, -6) == '_model')
				{
					$lkey = substr($lkey, 0, -6);
				}

			    if( ! (isset($hashes[$lkey]) && $hashes[$lkey] == $val['hash']))
			    {
			        $itemarr[$name] = 1;
			    }
			}
		}
		catch (Exception $e) 
		{
			error('hash_check: '.$e->getMessage());
		}


		// Handle errors
		foreach($GLOBALS['alerts'] AS $type => $list)
		{
			foreach ($list AS $msg)
			{
				$itemarr['error'] .= "$type: $msg\n";
			}
		}
		
		// Return list of changed hashes
		echo serialize($itemarr);
	}
	
	/**
	 * Check in script for clients
	 *
	 * Clients check in client data using $_POST
	 *
	 * @author AvB
	 **/
	function check_in()
	{
	    if( ! isset($_POST['items']))
	    {
	    	$this->error("No items in POST");
	    }

		$arr = @unserialize($_POST['items']);

		if ( ! is_array($arr))
		{
			$this->error("Could not parse items, not a proper serialized file");
		}
		
		foreach($arr as $name => $val)
		{
			// Skip items without data
		    if ( ! isset($val['data'])) continue;

		    // Rename legacy InventoryItem to inventory
			$name = str_ireplace('InventoryItem', 'inventory', $name);

		   	alert("starting: $name");

		   	// All models are lowercase
		   	$name = strtolower($name);

		   	if(preg_match('/[^\da-z_]/', $name))
		   	{
		   		$this->msg("Model has an illegal name: $name");
		    	continue;
		   	}
			
			// All models should be part of a module
			if(substr($name, -6) == '_model')
			{
				$module = substr($name, 0, -6);
				
			}
			else // Legacy clients
			{
				$module = $name;
				$name = $module . '_model';
			}

			$model_path = APP_PATH."modules/${module}/";

			// Capitalize classname
		   	$classname = ucfirst($name);
		    
		    // Todo: prevent admin and user models, sanitize $name
		    if( ! file_exists($model_path . $name . '.php'))
		    {
		    	$this->msg("Model not found: $name");
		    	continue;
		    }
		    require_once($model_path . $name . '.php');

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
		        $hash = new Hash($_POST['serial'], $module);
		        $hash->hash = $val['hash'];
				$hash->timestamp = time();
		        $hash->save();
	       		
	       	} catch (Exception $e) {
	       		$this->msg("An error occurred while processing: $classname");
	       		$this->msg("Error: " . $e->getMessage());	       		
	       	}

	       	// Handle alerts
			foreach($GLOBALS['alerts'] AS $type => $list)
			{
				foreach ($list AS $msg)
				{
					$this->msg("$type: $msg");
				}

				// Remove alert from array
				unset($GLOBALS['alerts'][$type]);
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
		echo('Server '.$msg."\n");
		if($exit)
		{
			exit();
		}
	}

	/**
	 * Echo serialized array with error
	 * and exit
	 *
	 * @param string message
	 * @author AvB
	 **/
	function error($msg)
	{
		echo serialize(array('error' =>$msg));
		exit();
	}
	
}
