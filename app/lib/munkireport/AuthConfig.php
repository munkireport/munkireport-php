<?php

namespace munkireport\lib;
use \Exception;
use Hautelook\Phpass\PasswordHash;

class AuthConfig extends AbstractAuth
{
    private $mechanism, $config, $authStatus, $login;

    public function __construct($config)
    {
        $this->config = $config;
        $this->mechanism = 'config';
    }
    
    public function login($login, $password)
    {
        $this->authStatus = 'not_found';
        $this->login = $login;
        
        if ($login){
            $storedPassword = $this->findHashForLogin($login);
            
            if ($storedPassword) {
                $t_hasher = $this->load_phpass();
                if ($t_hasher->CheckPassword($password, $storedPassword)) {
                    $this->authStatus = 'success';
                    return true;
                }
                $this->authStatus = 'failed';
            }
        }
        
        return false;
    }
    
    public function getAuthMechanism()
    {
        return $this->mechanism;
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
        $groups = [];
        foreach (conf('groups', array()) as $groupname => $members) {
            if (in_array($this->login, $members)) {
                $groups[] = $groupname;
            }
        }
        return $groups;
    }
    
    private function findHashForLogin($login)
    {
        return isset($this->config[$login]) ? $this->config[$login] : false;
    }

    public function load_phpass()
    {
        return new PasswordHash(8, true);
    }

}