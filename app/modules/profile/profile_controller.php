<?php 
/**
 * profile list module class
 *
 * @package munkireport
 * @author
 **/
class Profile_controller extends Module_controller
{
	
	/*** Protect methods with auth! ****/
	function __construct()
	{
		// Store module path
        $this->module_path = dirname(__FILE__) .'/';
        $this->view_path = $this->module_path . 'views/';
	}
	/**
	 * Default method
	 *
	 * @author
	 **/
	function index()
	{
		echo "You've loaded the profile module!";
	}

	function items($name='', $payload='') 
    {
        // Protect this handler
        if( ! $this->authorized())
        {
            redirect('auth/login');
        }

        $data['profile_items'] = array();
        $data['name'] = 'No item';

        if ($name)
        {
            $name = rawurldecode($name);
            $profile_item_obj = new Profile_model();
            $data['profile_name'] = $name;
            if ($payload)
            {
                $payload = rawurldecode($payload);
                $items = $profile_item_obj->retrieve_many(
                    'payload_name = ? GROUP BY serial_number', array($payload));
                    $data['name'] = $payload;
            } else {
                $items = $profile_item_obj->retrieve_many(
                    'profile_name = ? GROUP BY serial_number', array($name));
                    $data['name'] = $name;
            }
            
            foreach ($items as $item)
            {
                $machine = new Machine_model($item->serial_number);

                // Check if authorized for this serial
                if( ! $machine->id )
                {
                    continue;
                }

                $instance['serial'] = $item->serial_number;
                $instance['hostname'] = $machine->computer_name;
                $instance['payload'] = $item->profile_name;
                $data['profile_items'][] = $instance;
            }
            
        }

        $obj = new View();
        $obj->view('profileitem_detail', $data, $this->view_path);
    }
	
} // END class default_module
