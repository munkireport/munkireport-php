<?php

namespace munkireport\lib;

class AuthEnv extends AbstractAuth
{
    private $config, $login, $authStatus;

    public function __construct($config)
    {
        $this->config = $config;
    }
    
    public function login($login, $password)
    {
        $this->login = getenv($this->config['env_user_var']);

        if ($this->config['env_user_deny_empty'] && empty($this->login)) {
            if ($this->login === '') {
                $this->authStatus = 'unauthorized';
            } elseif ($this->login === false) {
                $this->authStatus = 'failed';
            }

            return false;
        }

        $this->authStatus = 'success';
        return true;
    }
    
    public function getAuthMechanism()
    {
        return 'env';
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
        return [];
    }
}
