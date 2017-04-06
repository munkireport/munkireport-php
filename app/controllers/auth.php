<?php

namespace munkireport\controller;

use \Controller, \View, \Machine_group, \Business_unit, \Reportdata_model;
use munkireport\lib\Recaptcha, Hautelook\Phpass\PasswordHash;

class Auth extends Controller
{
    // Authentication mechanisms we handle
    public $mechanisms = array('noauth', 'config', 'ldap', 'AD');

    // Authentication mechanisms available
    public $auth_mechanisms = array();

    public function __construct()
    {
        if (conf('auth_secure') && empty($_SERVER['HTTPS'])) {
            redirect('error/client_error/426'); // Switch protocol
        }
        // Check if there's a valid auth mechanism in config
        $auth_mechanisms = array();
        $authSettings = conf('auth');
        foreach ($this->mechanisms as $mech) {
            if (isset($authSettings["auth_$mech"]) && is_array($authSettings["auth_$mech"])) {
                $this->auth_mechanisms[$mech] = $authSettings["auth_$mech"];
            }
        }
    }

    //===============================================================

    public function index()
    {
        redirect('auth/login');
    }

    public function login($return = '')
    {
        if (func_get_args()) {
            $return_parts = func_get_args();
            $return = implode('/', $return_parts);
        }

        if ($this->authorized()) {
            redirect($return);
        }

        $auth_verified = false;
        $pre_auth_failed = false;

        // If no valid mechanisms found, bail
        if (! $this->auth_mechanisms) {
            redirect('auth/generate');
        }

        $login = post('login');
        $password = post('password');

        //check for recaptcha
        if (conf('recaptchaloginpublickey')) {
        //recaptcha enabled by admin; checking it
            if ($response = post('g-recaptcha-response')) {
                $recaptchaObj = new Recaptcha(conf('recaptchaloginprivatekey'));
                $remote_ip = getRemoteAddress();

                if (! $recaptchaObj->verify($response, $remote_ip)) {
                    error('', 'auth.capthca.invalid');
                    $pre_auth_failed = true;
                }
            } else {
                if ($_POST) {
                    error('', 'auth.captcha.empty');
                    $pre_auth_failed = true;
                }
            }
        }

        // User is a member of these groups
        $groups = array();

        // Loop through authentication mechanisms
        // Break when we have a match
        foreach ($this->auth_mechanisms as $mechanism => $auth_data) {
        // Check if pre-authentication is successful
            if ($pre_auth_failed) {
                break;
            }

            // Local is just a username => hash array
            switch ($mechanism) {
                case 'noauth': // No authentication
                    $auth_verified = true;
                    $login = 'admin';
                    break 2;

                case 'config': // Config authentication
                    if ($login && $password) {
                        if (isset($auth_data[$login])) {
                            $t_hasher = $this->load_phpass();
                            $auth_verified = $t_hasher->CheckPassword($password, $auth_data[$login]);

                            if ($auth_verified) {
                            // Get group memberships
                                foreach (conf('groups', array()) as $groupname => $members) {
                                    if (in_array($login, $members)) {
                                        $groups[] = $groupname;
                                    }
                                }
                            }
                            break 2;
                        }
                    }
                    break;

                case 'ldap': // LDAP authentication
                    if ($login && $password) {
                        include_once(APP_PATH . '/lib/authLDAP/authLDAP.php');
                        $ldap_auth_obj = new \Auth_ldap($auth_data);
                        if ($ldap_auth_obj->authenticate($login, $password)) {
                        //alert('Authenticated');
                            // Check user against users list
                            if (isset($auth_data['mr_allowed_users'])) {
                                $admin_users = is_array($auth_data['mr_allowed_users']) ? $auth_data['mr_allowed_users'] : array($auth_data['mr_allowed_users']);
                                if (in_array(strtolower($login), array_map('strtolower', $admin_users))) {
                                    $auth_verified = true;

                                    // If business units enabled, get group memberships
                                    if (conf('enable_business_units')) {
                                        if ($user_data = $ldap_auth_obj->getUserData($login)) {
                                            $groups = $user_data['grps'];
                                        }
                                    }

                                    break 2;
                                }
                            }
                            // Check user against group list
                            if (isset($auth_data['mr_allowed_groups'])) {
                            // Set mr_allowed_groups to array
                                $admin_groups = is_array($auth_data['mr_allowed_groups']) ? $auth_data['mr_allowed_groups'] : array($auth_data['mr_allowed_groups']);
                                // Get groups from AD
                                if ($user_data = $ldap_auth_obj->getUserData($login)) {
                                    foreach ($user_data['grps'] as $group) {
                                        if (in_array($group, $admin_groups)) {
                                            $auth_verified = true;

                                            // If business units enabled, store group memberships
                                            if (conf('enable_business_units')) {
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
                    if ($_POST && $login && $password) {
                    //include the class and create a connection
                        //TODO: wrap this include somewhere else?
                        include_once(APP_PATH . '/lib/adLDAP/adLDAP.php');
                        try {
                            $adldap = new \adLDAP($auth_data);
                        } catch (adLDAPException $e) {
                            error('An error occurred while contacting AD', 'error_contacting_AD');
                            // When in debug mode, show additional info
                            if (conf('debug')) {
                                error($e->getMessage());
                            }
                            break 2;
                        }
                        // If nothing has failed to this point, authenticate user
                        if ($adldap->authenticate($login, $password)) {
                        // Check user against userlist
                            if (isset($auth_data['mr_allowed_users'])) {
                                $admin_users = is_array($auth_data['mr_allowed_users']) ? $auth_data['mr_allowed_users'] : array($auth_data['mr_allowed_users']);
                                if (in_array(strtolower($login), array_map('strtolower', $admin_users))) {
                                    $auth_verified = true;

                                    // If business units enabled, get group memberships
                                    if (conf('enable_business_units')) {
                                        $groups = $adldap->user()->groups($login);
                                    }

                                    break 2;
                                }
                            }
                            // Check user against group list
                            if (isset($auth_data['mr_allowed_groups'])) {
                            // Set mr_allowed_groups to array
                                $admin_groups = is_array($auth_data['mr_allowed_groups']) ? $auth_data['mr_allowed_groups'] : array($auth_data['mr_allowed_groups']);
                                // Get groups from AD
                                $groups = $adldap->user()->groups($login);
                                foreach ($groups as $group) {
                                    if (in_array($group, $admin_groups)) {
                                        $auth_verified = true;
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
                    die('Unknown authentication mechanism: '.$mechanism);
                    break;
            } //end switch
        } //end foreach loop

        // If authentication succeeded, create session
        if ($auth_verified) {
            $_SESSION['user'] = $login;
            $_SESSION['groups'] = $groups;
            $_SESSION['auth'] = $mechanism;

            $this->set_session_props();

            session_regenerate_id();
            redirect($return);
        }

        // If POST and no other alerts, auth has failed
        if ($_POST && ! $GLOBALS['alerts']) {
            if (! $login or ! $password) {
                error('Empty values are not allowed', 'auth.empty_not_allowed');
            } else {
                error('Wrong username or password', 'auth.wrong_user_or_pass');
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
    public function set_session_props($show = false)
    {
        // Initialize session
        $this->authorized();

        // Check if we are in a session
        if (! isset($_SESSION['auth'])) {
            $msg = array('error' => 'unauthorized');
            $obj = new View();
            $obj->view('json', array('msg' => $msg));
            return;
        }

        // Default role is user
        $_SESSION['role'] = 'user';
        $_SESSION['role_why'] = 'Default role';

        // Find role in config for current user
        foreach (conf('roles', array()) as $role => $members) {
        // Check for wildcard
            if (in_array('*', $members)) {
                $_SESSION['role'] = $role;
                $_SESSION['role_why'] = 'Matched on wildcard (*) in '.$role;
                break;
            }

            // Check if user or group is present in members
            foreach ($members as $member) {
                if (strpos($member, '@') === 0) {
                    // groups (start with @)
                    if (in_array(substr($member, 1), $_SESSION['groups'])) {
                        $_SESSION['role'] = $role;
                        $_SESSION['role_why'] = 'member of ' . $member;

                        break 2;
                    }
                } else {
                    // user
                    if ($member == $_SESSION['user']) {
                        $_SESSION['role'] = $role;
                        $_SESSION['role_why'] = $member. ' in "'.$role.'" role array';
                        break 2;
                    }
                }
            }
        }

        // Check if Business Units are enabled in the config file
        $bu_enabled = conf('enable_business_units', false);

        // Check if user is global admin
        if ($_SESSION['auth'] == 'noauth' or $_SESSION['role'] == 'admin') {
            unset($_SESSION['business_unit']);
        } elseif (! $bu_enabled) {
        // Regular user w/o business units enabled
            unset($_SESSION['business_unit']);
        } elseif ($bu_enabled) {
        // Authorized user, not in business unit
            $_SESSION['role'] = 'nobody';
            $_SESSION['role_why'] = 'Default role for Business Units';
            $_SESSION['business_unit'] = 0;

            // Lookup user in business units
            $bu = new Business_unit;
            if ($bu->retrieveOne("property IN ('manager', 'user') AND value=?", $_SESSION['user'])) {
                $_SESSION['role'] = $bu->property; // manager, user
                $_SESSION['role_why'] = $_SESSION['user'].' found in Business Unit '. $bu->unitid;
                $_SESSION['business_unit'] = $bu->unitid;
            } else {
                // Lookup groups in Business Units
                foreach ($_SESSION['groups'] as $group) {
                    if ($bu->retrieveOne("property IN ('manager', 'user') AND value=?", '@' . $group)) {
                        $_SESSION['role'] = $bu->property; // manager, user
                        $_SESSION['role_why'] = 'Group "'. $group . '" found in Business Unit '. $bu->unitid;
                        $_SESSION['business_unit'] = $bu->unitid;
                        break;
                    }
                }
            }
        }

        // Set machine_groups
        if ($_SESSION['role'] == 'admin' or ! $bu_enabled) {
        // Can access all defined groups (from machine_group)
            // and used groups (from reportdata)
            $mg = new Machine_group;
            $report = new Reportdata_model;
            $_SESSION['machine_groups'] = array_unique(array_merge($report->get_groups(), $mg->get_group_ids()));
        } else {
            // Only get machine_groups for business unit
            $_SESSION['machine_groups'] = $bu->get_machine_groups($bu->unitid);
        }

        // Show current session info
        if ($show) {
            $obj = new View();
            $obj->view('json', array('msg' => $_SESSION));
        }
    }

    public function logout()
    {
        // Initialize session
        $this->authorized();

        // Destroy session;
        session_destroy();
        redirect('');
    }

    public function generate()
    {
        // Add a reason why generate is called
        $data = array('reason' => empty($this->auth_mechanisms) ? 'noauth' : 'none');

        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $data['login'] = isset($_POST['login']) ? $_POST['login'] : '';

        if ($_POST && (! $data['login'] or ! $password)) {
            error('Empty values are not allowed', 'auth.empty_not_allowed');
        }

        if ($data['login'] && $password) {
            $t_hasher = $this->load_phpass();
            $data['generated_pwd'] = $t_hasher->HashPassword($password);
        }

        $obj = new View();
        $obj->view('auth/generate_password', $data);
    }

    public function load_phpass()
    {
        return new PasswordHash(8, true);
    }
}
