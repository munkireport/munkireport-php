<?php

namespace munkireport\lib;

use Adldap\Adldap;
use Adldap\Schemas\ActiveDirectory as ActiveDirectorySchema;
use \Exception;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class AuthAD extends AbstractAuth
{
    private $config, $schema, $groups, $login, $authStatus;

    public function __construct($config)
    {
        $this->config = $config;
        // Schema support
        $schemaName = 'Adldap\\Schemas\\'.$this->config['schema'];
        $this->schema = new $schemaName;
        $this->config['schema'] = $schemaName;
        $this->groups = [];
    }

    public function login($login, $password)
    {
        $this->login = $login;
        if ($login && $password) {
          
            if (conf('debug'))
            {
                $logger = new Logger('AUTH_AD');
                $logger->pushHandler(new StreamHandler(APP_ROOT.'/storage/logs/auth.log', Logger::INFO));
                Adldap::setLogger($logger);
            }
            $adldap = new adLDAP;

            $adldap->addProvider(
                $this->stripMunkireportItemsFromConfig($this->config),
                $name = 'default'
            );

            try {
                $provider = $adldap->connect();
                if($provider->auth()->attempt($login, $password, ! $this->bindAsAdmin())){
                    $search = $provider->search();

                    if ($this->schema instanceof ActiveDirectorySchema) {
                        if (empty($this->config['account_suffix'])) {
                            $user = $search->users()->findByOrFail('userPrincipalName', $login);
                        } else {
                            $user = $search->users()->findOrFail($login);
                        }
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
         return $this->config['username'] && $this->config['password'];
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
