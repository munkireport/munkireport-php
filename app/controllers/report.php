<?php
class report extends Controller
{
    function hash_check()
	{
		// Check if we have a serial and data
		if( ! isset($_POST['serial'])) die('Serial is missing');
		if( ! isset($_POST['items'])) die('Items are missing');

		require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');
		
		// Create return object
		$out = new CFPropertyList();
		$out->add( $itemarr = new CFArray() );
		
		// Parse items
		$parser = new CFPropertyList();
		$parser->parse($_POST['items'], CFPropertyList::FORMAT_XML);
		
		// Get stored hashes from db
		$hash = new Hash();
		$hashes = $hash->all($_POST['serial']);
		
		// Compare sent hashes with stored hashes
		foreach($parser->toArray() as $key => $val)
		{
		    if( ! (isset($hashes[$key]) && $hashes[$key] == $val['hash']))
		    {
		        $itemarr->add( new CFString( $key ) );
		    }
		}
		
		// Return list of changed hashes
		echo $out->toXML();
	}
	
	function check_in()
	{
	    if( ! isset($_POST['items']))
	    {
	    	$this->msg("No items in POST", TRUE);
	    }
	    require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');
		$parser = new CFPropertyList();
		try {
			$parser->parse($_POST['items'], CFPropertyList::FORMAT_XML);
			$arr = $parser->toArray();
		} catch (Exception $e) {
			$this->msg("Could not parse items, not a proper plist file", TRUE);
		}
		
		foreach($arr as $key => $val)
		{
			// Skip items without data
		    if ( ! isset($val['data'])) continue;

		   	$this->msg("Starting: $key");

		    
		    // Todo: prevent admin and user models, sanitize $key
		    if( ! file_exists(APP_PATH . 'models/' . $key . '.php'))
		    {
		    	$this->msg("Model not found: $key");
		    	continue;
		    }
		    require_once(APP_PATH . 'models/' . $key . '.php');

		    if ( ! class_exists( $key, false ) )
		    {
		    	$this->msg("Class not found: $key");
		    	continue;
		    }

		   	// Load model
	        $class = new $key($_POST['serial']);

        	
	        if( ! method_exists($class, 'process'))
	        {
	        	$this->msg("No process method in: $key");
		    	continue;
	        }

	       	try {
	       		// Process data (todo: why do we have to convert to iso-8859?)
		        $class->process(iconv('UTF-8', 'ISO-8859-1//IGNORE',$val['data']));
				//$class->process($val['data']);
		
		        // Store hash
		        $hash = new Hash($_POST['serial'], $key);
		        $hash->hash = $val['hash'];
				$hash->timestamp = time();
		        $hash->save();
	       		
	       	} catch (Exception $e) {
	       		$this->msg("An error occurred while processing: $key");
	       		
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
