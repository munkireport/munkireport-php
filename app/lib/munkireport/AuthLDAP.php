<?php

namespace munkireport\lib;

class AuthLDAP extends AbstractAuth
{
    private $config, $groups, $login, $auth_status;

    public function __construct($config)
    {
        $this->config = $config;
        $this->groups = [];
    }
    
    public function login($login, $password)
    {
        $this->login = $login;
        
        if ($login && $password) {
            include_once(APP_PATH . '/lib/authLDAP/authLDAP.php');
            $ldap_auth_obj = new \Auth_ldap($auth_data);
            if ($ldap_auth_obj->authenticate($login, $password)) {
                
                // Get groups
                if ($user_data = $ldap_auth_obj->getUserData($login)) {
                    foreach($user_data['grps'] as $group){
                        $this->groups[] = $group;
                    }
                }
                
                $auth_data = [
                    'user' => $login,
                    'groups' => $this->groups,
                ]
                
                if ($this->authorizeUserAndGroups($this->config, $auth_data)){
                    $this->authStatus = 'success';
                    return true;
                }

                $this->authStatus = 'unauthorized';
                return false;
            }
        }
        
        return false;
    }
    
    public function getAuthMechanism()
    {
        return 'ldap';
    }

    public function getAuthStatus()
    {
        return $this->authStatus;
    }
    
    public function getUser()
    {
        return $this->login;
    }

    public function getGroups()
    {
        return $this->groups;
    }
}
