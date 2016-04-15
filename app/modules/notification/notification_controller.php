<?php 

/**
 * Notification module class
 *
 * @package munkireport
 * @author 
 **/
class Notification_controller extends Module_controller
{
	// For development set debug to true,
	// this will prevent the last_run to be updated.
	private $debug = false;
	
	/*** Protect methods with auth! ****/
	function __construct()
	{
		// Store module path
		$this->module_path = dirname(__FILE__);
		if( ! $this->authorized())
		{
			$obj = new View();
			$obj->view('json', array('msg' => array('error' => 'Not authorized')));
		}

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
	 * Manage notifications
	 * 
	 */
	public function manage()
	{
		if( ! $this->authorized('global'))
		{
			die('You need to be admin');
		}

		$obj = new View();
		$obj->view('notifications_manager', $vars='', $this->module_path.'/views/');

	}

	/**
	 * REST interface, returns json with notification objects
	 **/
	function get_list()
	{
        $obj = new View();
		$notify_obj = new Notification_model();
		$obj->view('json', array('msg' => $notify_obj->get_list()));
	}
	
	/**
	 * Save notification
	 *
	 * Save a notification objects
	 *
	 */
	public function save()
	{
		$obj = new View();
		$notify_obj = new Notification_model();
		$obj->view('json', array('msg' => $notify_obj->save($_POST)));
		
	}
	
	/**
	 * Run notification check
	 *
	 * Check notifications, notify if necessary
	 *
	 **/
	public function runCheck()
	{
				
		$now = time();
		$stats = array('errors' => 0, 'email' => 0, 'desktop' => 0);
		
		// Load notifications
		$notifyObj = new Notification_model();
		$notificationList = $notifyObj->getDueNotifications();
		
		// No due notifications?
		if( ! $notificationList){
			// Return JSON with results
			$obj->view('json', array('msg' => $stats));
			return;
		}
		
		// Get Machine Groups per Business Unit
		$buMgList = array();
		$buObj = new Business_unit();
		foreach($buObj->retrieve_many('property=?', array('machine_group')) AS $obj)
		{
			$buId = intval($obj->unitid);
			$buMgList[$buId][] = intval($obj->value);
		}
		
		$allEvents = array(); // $allEvents is used for email
		$eventObj = new Event_model();
		foreach ($notificationList as $notificationObj)
		{
			$events = array();
			$buId = $notificationObj->business_unit;
			
			// Match notification with event todo: move to event model
			$sql = sprintf("SELECT event.*, machine.computer_name 
				FROM event
				JOIN reportdata USING (serial_number)
				JOIN machine USING (serial_number)
				WHERE event.serial_number LIKE '%s'
				AND module LIKE '%s'
				AND msg LIKE '%s'
				AND type LIKE '%s'
				AND event.timestamp > %s",
				$notificationObj->serial_number,
				$notificationObj->notification_module,
				$notificationObj->notification_msg,
				$notificationObj->notification_severity,
				$notificationObj->last_run);

			// If business unit defined, add extra filter
			if ($buId != -1) {
				// Make a list that will return nothing.
				$machineGroupList = array(-1);
				if( isset($buMgList[$buId]))
				{
					// Add a list if it is valid
					$machineGroupList = $buMgList[$buId];
				}
				$sql .= sprintf(' AND reportdata.machine_group IN (%s) ', implode(',', $machineGroupList));
			}
						
			foreach($eventObj->query($sql) as $event)
			{
				$events[] = $event;
				// Store event per user/method
				$allEvents[$notificationObj->notification_how][$notificationObj->notification_who][] = $event;
			}
			
			// Update notification obj.
			$notificationObj->event_obj = json_encode($events);
			if( ! $this->debug){
				$notificationObj->last_run = $now;
			}
			$notificationObj->save();
		}
		
		// Get stats
		foreach ($stats as $key => $value) {
			$stats[$key] = isset($allEvents[$key]) ? count($allEvents[$key]) : $value;
		}
		
		// Check if we need to send email
		if($stats['email'])
		{
			// Get email template path FIXME make config item
			$template_path = dirname(__FILE__) .'/views/notification_email.php';
			
			// Load Email class until we can autoload an email library
			$email_config = conf('email');
			if($email_config)
			{
				include_once (APP_PATH . '/lib/munkireport/Email.php');
				include_once (APP_PATH . '/lib/munkireport/I18next.php');
				$i18nObj = new munkireport\localize\I18next($email_config['locale']);

				foreach($allEvents['email'] as $who => $event_array)
				{
					// Load email template
					$obj = new View($template_path, array(
						'events' => $event_array,
						'i18nObj' => $i18nObj,
					));
					$email['content'] = $obj->fetch();
					$email['subject'] = 'Munkireport Notification';
					$email['to'] = array($who => '');
					$email['vars'] = $event_array; // Format this?

					$mailer = new munkireport\email\Email($email_config);
					$result = $mailer->send($email);
					if($result['error'])
					{
						$stats['errors']++;
						$stats['error_msgs'][] = $result['error_msg'];
					}
				}
			}			
		}
		
		// notify
		
		// Return JSON with results
		$obj = new View();
		$obj->view('json', array('msg' => $stats));
		
	}

	
} // END class notification_module