<?php

namespace munkireport\lib;
use \Exception, \View, \Machine_group, \Business_unit, \Reportdata_model;

class AuthHandler
{
    // Authentication mechanisms we handle
    private $mechanisms = ['noauth', 'config', 'ldap', 'AD', 'saml'];

    // Authentication mechanisms available
    private $auth_mechanisms = [];
    
    public function __construct()
    {
        // Check if there's a valid auth mechanism in config
        $authSettings = conf('auth');
        foreach ($this->mechanisms as $mech) {
            if (isset($authSettings["auth_$mech"]) && is_array($authSettings["auth_$mech"])) {
                $this->auth_mechanisms[$mech] = $authSettings["auth_$mech"];
            }
        }
    }
    
    public function login($login, $password)
    {
        if($auth_obj = $this->validate($login, $password))
        {
            $this->storeAuthData($auth_obj);

            $this->setSessionProps();

            session_regenerate_id();

            return true;
        }
        return false;
    }
    
    private function validate($login, $password)
    {

        // Loop through authentication mechanisms
        // Break when we have a match
        foreach ($this->auth_mechanisms as $mechanism => $auth_data) {

            switch ($mechanism) {
                case 'noauth': // No authentication
                    $authObj = new AuthNoauth($auth_data);
                    if($authObj->login($login, $password)){
                        return $authObj;
                    }
                    break;
                    
                case 'saml':
                    redirect('auth/saml/sso');
                    break;
                    
                case 'config': // Config authentication
                    $authObj = new AuthConfig($auth_data);
                    if($authObj->login($login, $password)){
                        return $authObj;
                    }
                    if ($authObj->getAuthStatus() == 'failed'){
                        return false;
                    }
                    break;

                case 'ldap': // LDAP authentication
                    $authObj = new AuthLDAP($auth_data);
                    if($authObj->login($login, $password)){
                        return $authObj;
                    }
                    if ($authObj->getAuthStatus() == 'failed'){
                        return false;
                    }
                    if ($authObj->getAuthStatus() == 'unauthorized'){
                        error('Not authorized', 'auth.not_authorized');
                        return false;
                    }
                    break;

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

                                    // If business units enabled, get group memberships
                                    if (conf('enable_business_units')) {
                                        
                                        $groups = $adldap->user()->groups($login);
                                    }

                                    return true;
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
                                        return true;
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
        
        // Return state and groups.
        return;
    }
    
    public function authConfigured()
    {
        return count($this->getAuthMechanisms()) > 0;
    }
    
    public function getAuthMechanisms()
    {
        return $this->auth_mechanisms;
    }
    
    public function storeAuthData($authObj)
    {
        $this->storeSessionData([
            'user' => $authObj->getUser(),
            'groups' => $authObj->getGroups(),
            'auth' => $authObj->getAuthMechanism(),
        ]);
    }
    
    private function storeSessionData($session_data)
    {
        foreach($session_data as $key => $value){
            $_SESSION[$key] = $value;
        }
    }

    /**
     * Set session properties
     *
     **/
    public function setSessionProps($show = false)
    {
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
        
        return $_SESSION;
    }

}