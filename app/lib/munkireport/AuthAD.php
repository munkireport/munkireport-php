<?php

namespace munkireport\lib;

use Adldap\Adldap, \Exception;

class AuthAD extends AbstractAuth
{
    private $config, $schema, $groups, $login, $auth_status;

    public function __construct($config)
    {
        $this->config = $config;
        // Schema support
        if (isset($this->config['schema'])){
            $schemaName = 'Adldap\\Schemas\\'.$this->config['schema'];
            $this->schema = new $schemaName;
            unset($this->config['schema']);
        } else {
            $this->schema = new ActiveDirectory;
        }
        $this->groups = [];
    }

    public function login($login, $password)
    {
        $this->login = $login;
        if ($login && $password) {
            $adldap = new adLDAP;

            $adldap->addProvider(
                $this->stripMunkireportItemsFromConfig($this->config),
                $name = 'default',
                null,
                $this->schema
            );

            try {
                $provider = $adldap->connect();
                if($provider->auth()->attempt($login, $password, ! $this->bindAsAdmin())){
                    $search = $provider->search();

                    if ($this->schema instanceof ActiveDirectory) {
                        $user = $search->users()->findOrFail($login);
                    }
                    else {
                        $user = $search->users()->findByOrFail('uid', $login);
                    }
                    $this->groups = $user->getGroupNames($this->recursiveGroupSearch());

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

    private function recursiveGroupSearch()
    {
        return isset($this->config['mr_recursive_groupsearch']) ? $this->config['mr_recursive_groupsearch'] : false;
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
