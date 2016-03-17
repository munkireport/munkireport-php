<?php 

/**
 * Notification module class
 *
 * @package munkireport
 * @author 
 **/
class Notification_controller extends Module_controller
{
	
	/*** Protect methods with auth! ****/
	function __construct()
	{
		// Store module path
		$this->module_path = dirname(__FILE__);
	}

	/**
	 * Default method
	 *
	 * @author AvB
	 **/
	function index()
	{
		echo "You've loaded the notification module!";
	}

	/**
	 * REST interface, returns json with notification objects
	 **/
	function get_list()
	{
		
        $obj = new View();
		if( ! $this->authorized())
		{
			$obj->view('json', array('msg' => array('error' => 'Not authorized')));
		}
		else
		{
			$notify_obj = new Notification_model();
			$obj->view('json', array('msg' => $notify_obj->get_list()));
		}

	}
	
	/**
	 * Run notification check
	 *
	 * Check notifications, notify if necessary
	 *
	 **/
	public function runCheck()
	{
		$obj = new View();
		if( ! $this->authorized())
		{
			$obj->view('json', array('msg' => array('error' => 'Not authorized')));
		}
		else
		{
			// Load notifications
			$notifyObj = new Notification_model();
			$notificationList = $notifyObj->getDueNotifications();
			
			// Load events
			if($notificationList)
			{
				$eventObj = new Event_model();
				$allEvents = array();
				foreach ($notificationList as $notificationObj)
				{
					$events = array();
					// Match notification with event
					$sql = sprintf("SELECT serial_number, msg, data 
						FROM event 
						WHERE serial_number LIKE '%s'
						AND module LIKE '%s'
						AND type LIKE '%s'",
						$notificationObj->serial_number,
						$notificationObj->notification_module,
						$notificationObj->notification_severity);
					foreach($eventObj->query($sql) as $event)
					{
						$events[] = $event;
						// Store event per user/method
						$allEvents[$notificationObj->notification_how][$notificationObj->notification_who][] = $event;
					}
					
					// Update notification obj.
					$notificationObj->event_obj = json_encode($events);
					$notificationObj->last_notified = time();
					$notificationObj->save();
					
				}
				
				

			}
			
			
			
			
			// notify
			
			// Return JSON with results
			$obj->view('json', array('msg' => $events));
		}
		
	}

	
} // END class notification_module