<?php

namespace munkireport\lib;

class AuthEnv extends AbstractAuth
{
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }
    
    public function login($login, $password)
    {
        return true;
    }
    
    public function getAuthMechanism()
    {
        return 'env';
    }

    public function getAuthStatus()
    {
        return 'success';
    }
    
    public function getUser()
    {
        return getenv($this->config['env_user_var']);
    }

    public function getGroups()
    {
        return [];
    }
}
