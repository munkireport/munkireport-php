<?php

namespace munkireport\lib;

class AuthNoauth extends AbstractAuth
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
        return 'noauth';
    }

    public function getAuthStatus()
    {
        return 'success';
    }
    
    public function getUser()
    {
        return 'admin';
    }

    public function getGroups()
    {
        return [];
    }
}
