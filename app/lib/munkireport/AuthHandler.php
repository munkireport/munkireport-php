<?php

namespace munkireport\lib;
use \Exception, \View, \Reportdata_model;
use munkireport\models\Business_unit;
use munkireport\models\Machine_group;

class AuthHandler
{
    // Authentication mechanisms we handle => classname
    private $mechanisms = [
        'noauth' => 'AuthNoauth',
        'local' => 'AuthLocal',
        'ldap' => 'AuthLDAP',
        'AD' => 'AuthAD',
        'saml' => 'AuthSaml'];

    // Authentication mechanisms available
    private $auth_mechanisms = [];
    
    public function __construct()
    {
        // Check if there's a valid auth mechanism in config
        $authSettings = conf('auth');
        foreach ($this->mechanisms as $mech => $class_name) {
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
            
            try {
                $class_name = 'munkireport\\lib\\' . $this->mechanisms[$mechanism];
                $authObj = new $class_name($auth_data, $this);
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
            } catch (Exception $e) {
                error('An error occurred', 'auth.not_authorized');
                if (conf('debug')) {
                    error($e->getMessage());
                }
            }
        } //end foreach loop
        
        return false;
    }
    
    public function getConfig($authmethod)
    {
        return isset($this->auth_mechanisms[$authmethod]) ? $this->auth_mechanisms[$authmethod]: [];
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
        foreach (conf('roles') as $role => $members) {
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
        try {
            if ($_SESSION['role'] == 'admin' or ! $bu_enabled) {
            // Can access all defined groups (from machine_group)
                // and used groups (from reportdata)
                $mg = new Machine_group;
                $machine_groups = Reportdata_model::select('machine_group')
                    ->groupBy('machine_group')
                    ->get()
                    ->pluck('machine_group')
                    ->toArray();
                $machine_groups = $machine_groups ? $machine_groups : [0];
                $_SESSION['machine_groups'] = array_unique(array_merge($machine_groups, $mg->get_group_ids()));
            } else {
                // Only get machine_groups for business unit
                $_SESSION['machine_groups'] = $bu->get_machine_groups($bu->unitid);
            }
            $_SESSION['initialized'] = true;
        } catch (\Exception $e) {
            $_SESSION['machine_groups'] = [0];
            $_SESSION['initialized'] = false;
        }

        return $_SESSION;
    }

}

