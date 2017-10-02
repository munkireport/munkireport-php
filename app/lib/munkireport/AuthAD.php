<?php

namespace munkireport\lib;

use \Adldap\Adldap, \Exception;


class AuthAD extends AbstractAuth
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
            $adldap = new adLDAP;
            $adldap->addProvider($auth_data);

            try {
                $provider = $adldap->connect();
                if($provider->auth()->attempt($login, $password)){
                    $user = $provider->search()->find($login);
                    $this->groups = $user->getGroupNames();

                    $auth_data = [
                        'user' => $login,
                        'groups' => $this->groups,
                    ];

                    if ($this->authorizeUserAndGroups($this->config, $auth_data)){
                        $this->authStatus = 'success';
                        return true;
                    }

                    $this->authStatus = 'unauthorized';
                    return false;

                }

            } catch (Exception $e) {
                error('An error occurred while contacting AD', 'error_contacting_AD');
                // When in debug mode, show additional info
                if (conf('debug')) {
                    error($e->getMessage());
                }
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
