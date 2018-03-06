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
            $adldap->addProvider($this->stripMunkireportItemsFromConfig($this->config));

            try {
                $provider = $adldap->connect();
                if($provider->auth()->attempt($login, $password, ! $this->bindAsAdmin())){
                    $search = $provider->search();

                    $user = $search->findOrFail($login);
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

    private function bindAsAdmin()
    {
         return isset($this->config['admin_username']) && isset($this->config['admin_password']);
    }

    public function getAuthMechanism()
    {
        return 'ldap';
    }

    public function getAuthStatus()
    {
        return $this->auth_status;
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
