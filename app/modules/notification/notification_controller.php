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
			$now = time();
			$stats = array('errors' => 0, 'email' => 0, 'desktop' => 0);
			
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
					// Match notification with event todo: move to model
					$sql = sprintf("SELECT * 
						FROM event 
						WHERE serial_number LIKE '%s'
						AND module LIKE '%s'
						AND msg LIKE '%s'
						AND type LIKE '%s'
						AND timestamp > %s",
						$notificationObj->serial_number,
						$notificationObj->notification_module,
						$notificationObj->notification_msg,
						$notificationObj->notification_severity,
						$notificationObj->last_run);

					foreach($eventObj->query($sql) as $event)
					{
						$events[] = $event;
						// Store event per user/method
						$allEvents[$notificationObj->notification_how][$notificationObj->notification_who][] = $event;
					}
					
					// Update notification obj.
					$notificationObj->event_obj = json_encode($events);
					//$notificationObj->last_run = $now;
					$notificationObj->save();
				}
				
				// Get stats
				foreach ($stats as $key => $value) {
					$stats[$key] = isset($allEvents[$key]) ? count($allEvents[$key]) : $value;
				}
				
				// Check if we need to send email
				if($stats['email'])
				{
					// Load Email class until we can autoload an email library
					$email_config = conf('email');
					if($email_config)
					{
						foreach($allEvents['email'] as $who => $event_array)
						{
							// Load email template
							$obj = new View(VIEW_PATH.'email/notification.php', array('events' => $event_array ));
							$email['content'] = $obj->fetch();
							$email['subject'] = 'Munkireport Notification';
							$email['to'] = array($who => '');
							$email['vars'] = $event_array; // Format this?
							include_once (APP_PATH . '/lib/munkireport/Email.php');
							$mailer = new munkireport\email\Email($email_config);
							$mailer->send($email);

						}
					}
				}
				
			}
			
			// notify
			
			// Return JSON with results
			$obj->view('json', array('msg' => $stats));
		}
		
	}

	
} // END class notification_module