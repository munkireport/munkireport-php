<?php
class auth extends Controller
{
	// Authentication mechanisms we handle
	public $mechanisms = array('noauth', 'config', 'ldap', 'AD');

	// Authentication mechanisms available
	public $auth_mechanisms = array();

	function __construct()
	{
		if(conf('auth_secure') && empty($_SERVER['HTTPS']))
		{
			redirect('error/client_error/426'); // Switch protocol
		}
		// Check if there's a valid auth mechanism in config
		$auth_mechanisms = array();
		$authSettings = conf('auth');
		foreach($this->mechanisms as $mech)
		{
			if (isset($authSettings["auth_$mech"]) && is_array($authSettings["auth_$mech"]))
			{
				$this->auth_mechanisms[$mech] = $authSettings["auth_$mech"];
			}
		}
	}

	//===============================================================

	function index()
	{
		redirect('auth/login');
	}

	function login($return = '')
	{
		if(func_get_args())
		{
			$return_parts = func_get_args();
			$return = implode('/', $return_parts);

		}
		
		if($this->authorized())
		{
			redirect($return);
		}

		$check = FALSE;

		// If no valid mechanisms found, bail
		if ( ! $this->auth_mechanisms)
		{
			redirect('auth/generate');
		}

		$login = isset($_POST['login']) ? $_POST['login'] : '';
		$password = isset($_POST['password']) ? $_POST['password'] : '';
	        $recaptcharesponse = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';
	
		//check for recaptcha
		if(!empty(conf('recaptchaloginpublickey')))
		{
			//recaptcha enabled by admin; checking it
		        if(!empty($recaptcharesponse))
		        {
		        	//verifying recaptcha with google
		        	try
		        	{
	                		$userip = $_SERVER["REMOTE_ADDR"];
	                		$secreykey = conf('recaptchaloginprivatekey');
	                    		$url = 'https://www.google.com/recaptcha/api/siteverify';
				        $data = ['secret'   => $secreykey,
				                 'response' => $_POST['g-recaptcha-response'],
				                 'remoteip' => $userip];
				        $options = [
				            'http' => [
				                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				                'method'  => 'POST',
				                'content' => http_build_query($data) 
				            ]
				        ];
				        $context  = stream_context_create($options);
				        $result = file_get_contents($url, false, $context);
					$result = json_decode($result)->success;
			    
					if($result != 1)
					{
				        	//recaptcha failed to verify
						$recaptcharesponse = false;
					}
					else
					{
						//recaptcha verified successfully
						$recaptcharesponse = true;
					}
		        	}
    				catch (Exception $e)
    				{
        				error('Invalid captcha response', 'auth.invalid_captcha');
    				}
		        }
		}
	        else
	        {
	        	//recaptcha not enabled by admin; skipping it
	        	$recaptcharesponse = true;
	        }

		// User is a member of these groups
		$groups = array();

		// Loop through authentication mechanisms
		// Break when we have a match
		foreach($this->auth_mechanisms as $mechanism => $auth_data)
		{
			// Local is just a username => hash array
			switch ($mechanism)
			{
				case 'noauth': // No authentication
					$check = TRUE;
					$login = 'admin';
					break 2;

				case 'config': // Config authentication
					if($login && $password && $recaptcharesponse)
					{
						if(isset($auth_data[$login]))
						{
							$t_hasher = $this->load_phpass();
							$check = $t_hasher->CheckPassword($password, $auth_data[$login]);
							
							if($check)
							{
								// Get group memberships
								foreach(conf('groups', array()) AS $groupname => $members)
								{
									if(in_array($login, $members))
									{
										$groups[] = $groupname;
									}
								}
							}
							break 2;
						}
					}
					break;

				case 'ldap': // LDAP authentication
					if ($login && $password && $recaptcharesponse)
					{
						include_once (APP_PATH . '/lib/authLDAP/authLDAP.php');
						$ldap_auth_obj = new Auth_ldap($auth_data);
						if ($ldap_auth_obj->authenticate($login, $password))
						{
							//alert('Authenticated');
							// Check user against users list
							if(isset($auth_data['mr_allowed_users']))
							{
								$admin_users = is_array($auth_data['mr_allowed_users']) ? $auth_data['mr_allowed_users'] : array($auth_data['mr_allowed_users']);
								if (in_array(strtolower($login),array_map('strtolower', $admin_users)))
								{
									$check = TRUE;

									// If business units enabled, get group memberships
									if(conf('enable_business_units'))
									{
										if( $user_data = $ldap_auth_obj->getUserData($login))
										{
											$groups = $user_data['grps'];
										}
									}

									break 2;
								}
							}
							// Check user against group list
							if(isset($auth_data['mr_allowed_groups']))
							{
								// Set mr_allowed_groups to array
								$admin_groups = is_array($auth_data['mr_allowed_groups']) ? $auth_data['mr_allowed_groups'] : array($auth_data['mr_allowed_groups']);
								// Get groups from AD
								if( $user_data = $ldap_auth_obj->getUserData($login))
								{
									foreach ($user_data['grps'] as $group)
									{
										if (in_array($group, $admin_groups))
										{
											$check = TRUE;

											// If business units enabled, store group memberships
											if(conf('enable_business_units'))
											{
												$groups = $user_data['grps'];
											}
											
											break 3;
										}
									}
								}
							}//end group list check
							// Not in users list or group list
							error('Not authorized', 'auth.not_authorized');
							break;
						}
					}

				case 'AD': // Active Directory authentication
					// Prevent empty values
					if ($_POST && $login && $password && $recaptcharesponse)
					{
						//include the class and create a connection
						//TODO: wrap this include somewhere else?
						include_once (APP_PATH . '/lib/adLDAP/adLDAP.php');
						try
						{
							$adldap = new adLDAP($auth_data);
						}
						catch (adLDAPException $e)
						{
							error('An error ocurred while contacting AD', 'error_contacting_AD');
							// When in debug mode, show additional info
							if (conf('debug'))
							{
								error($e->getMessage());
							}
							break 2;
						}
						// If nothing has failed to this point, authenticate user
						if ($adldap->authenticate($login, $password))
						{
							// Check user against userlist
							if(isset($auth_data['mr_allowed_users']))
							{
								$admin_users = is_array($auth_data['mr_allowed_users']) ? $auth_data['mr_allowed_users'] : array($auth_data['mr_allowed_users']);
								if (in_array(strtolower($login),array_map('strtolower', $admin_users)))
								{
									$check = TRUE;

									// If business units enabled, get group memberships
									if(conf('enable_business_units'))
									{
										$groups = $adldap->user()->groups($login);
									}

									break 2;
								}
							}
							// Check user against group list
							if(isset($auth_data['mr_allowed_groups']))
							{
								// Set mr_allowed_groups to array
								$admin_groups = is_array($auth_data['mr_allowed_groups']) ? $auth_data['mr_allowed_groups'] : array($auth_data['mr_allowed_groups']);
								// Get groups from AD
								$groups = $adldap->user()->groups($login);
								foreach ($groups as $group)
								{
									if (in_array($group, $admin_groups))
									{
										$check = TRUE;
										break 3;
									}
								}
							}//end group list check

							// Not in users list or group list
							error('Not authorized', 'auth.not_authorized');
							break;
						}
						break;
					}
					break; //end of AD method

				default:
					die( 'Unknown authentication mechanism: '.$mechanism);
					break;
			} //end switch
		} //end foreach loop

		// If authentication succeeded, create session
		if($check)
		{
			$_SESSION['user'] = $login;
			$_SESSION['groups'] = $groups;
			$_SESSION['auth'] = $mechanism;
			
			$this->set_session_props();

			session_regenerate_id();
			redirect($return);
		}

		// If POST and no other alerts, auth has failed
		if($_POST && ! $GLOBALS['alerts'])
		{
	                if( ! $recaptcharesponse )
	                {
	                	error('Invalid captcha response', 'auth.invalid_captcha');
	                }
	                else
	                {
				if( ! $login OR ! $password )
				{
					error('Empty values are not allowed', 'auth.empty_not_allowed');
				}
				else
				{
					error('Wrong username or password', 'auth.wrong_user_or_pass');
				}
	                }
		}

		$data = array('login' => $login, 'url' => url("auth/login/$return"));

		$obj = new View();
		$obj->view('auth/login', $data);
	}

	/**
	 * Set session properties
	 *
	 **/
	function set_session_props($show = false)
	{
		// Initialize session
		$this->authorized();

		// Check if we are in a session
		if( ! isset($_SESSION['auth']))
		{
			$msg = array('error' => 'unauthorized');
			$obj = new View();
	        $obj->view('json', array('msg' => $msg));
			return;
		}

		// Default role is user
		$_SESSION['role'] = 'user';
		$_SESSION['role_why'] = 'Default role';

		// Find role in config for current user
		foreach(conf('roles', array()) AS $role => $members)
		{
			// Check for wildcard
			if(in_array('*', $members))
			{
				$_SESSION['role'] = $role;
				$_SESSION['role_why'] = 'Matched on wildcard (*) in '.$role;
				break;
			}

			// Check if user or group is present in members
			foreach($members as $member)
			{
				if(strpos($member, '@') === 0)
				{
					// groups (start with @)
					if(in_array(substr($member, 1), $_SESSION['groups']))
					{
						$_SESSION['role'] = $role;
						$_SESSION['role_why'] = 'member of ' . $member;

						break 2;
					}
				}
				else
				{
					// user
					if($member == $_SESSION['user'])
					{
						$_SESSION['role'] = $role;
						$_SESSION['role_why'] = $member. ' in "'.$role.'" role array';
						break 2;
					}
				}
			}
		}

		// Check if Business Units are enabled in the config file
		$bu_enabled = conf('enable_business_units', FALSE);

		// Check if user is global admin
		if($_SESSION['auth'] == 'noauth' OR $_SESSION['role'] == 'admin')
		{
			unset($_SESSION['business_unit']);
		}
		elseif( ! $bu_enabled)
		{
			// Regular user w/o business units enabled
			unset($_SESSION['business_unit']);
		}
		elseif($bu_enabled)
		{
			// Authorized user, not in business unit
			$_SESSION['role'] = 'nobody';
			$_SESSION['role_why'] = 'Default role for Business Units';
			$_SESSION['business_unit'] = 0;

			// Lookup user in business units
			$bu = new Business_unit;
			if($bu->retrieve_one("property IN ('manager', 'user') AND value=?", $_SESSION['user']))
			{
				$_SESSION['role'] = $bu->property; // manager, user
				$_SESSION['role_why'] = $_SESSION['user'].' found in Business Unit '. $bu->unitid;
				$_SESSION['business_unit'] = $bu->unitid;
			}
			else
			{
				// Lookup groups in Business Units
				foreach($_SESSION['groups'] AS $group)
				{
					if($bu->retrieve_one("property IN ('manager', 'user') AND value=?", '@' . $group))
					{
						$_SESSION['role'] = $bu->property; // manager, user
						$_SESSION['role_why'] = 'Group "'. $group . '" found in Business Unit '. $bu->unitid;
						$_SESSION['business_unit'] = $bu->unitid;
						break;
					}
				}
			}
		}

		// Set machine_groups
		if($_SESSION['role'] == 'admin' OR ! $bu_enabled)
		{
			// Can access all defined groups (from machine_group)
			// and used groups (from reportdata)
			$mg = new Machine_group;
			$report = new Reportdata_model;
			$_SESSION['machine_groups'] = array_unique(array_merge($report->get_groups(), $mg->get_group_ids()));
		}
		else
		{
			// Only get machine_groups for business unit
			$_SESSION['machine_groups'] = $bu->get_machine_groups($bu->unitid);
		}

		// Show current session info
		if($show)
		{
			$obj = new View();
	        $obj->view('json', array('msg' => $_SESSION));
		}
	}

	function logout()
	{
		// Initialize session
		$this->authorized();

		// Destroy session;
		session_destroy();
		redirect('');
	}

	function generate()
	{
		// Add a reason why generate is called
		$data = array('reason' => empty($this->auth_mechanisms) ? 'noauth' : 'none');

		$password = isset($_POST['password']) ? $_POST['password'] : '';
		$data['login'] = isset($_POST['login']) ? $_POST['login'] : '';
		
		if( $_POST && (! $data['login'] OR ! $password))
		{
			error('Empty values are not allowed', 'auth.empty_not_allowed');
		}

		if ($data['login'] && $password)
		{
			$t_hasher = $this->load_phpass();
			$data['generated_pwd'] = $t_hasher->HashPassword($password);
		}

		$obj = new View();
		$obj->view('auth/generate_password', $data);
	}

	function load_phpass()
	{
		require(APP_PATH . '/lib/phpass-0.3/PasswordHash.php');
		return new PasswordHash(8, TRUE);
	}

}
