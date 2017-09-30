<?php

namespace munkireport\lib;
use \Exception;
use Hautelook\Phpass\PasswordHash;

class Auth_config
{
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }
    
    public function login($login, $password, &$groups)
    {
        if ( ! $login){
            return 'not_found';
        }
        
        if (isset($this->config[$login])) {
            $t_hasher = $this->load_phpass();
            $auth_verified = $t_hasher->CheckPassword($password, $this->config[$login]);

            if ($auth_verified) {
            // Get group memberships
                foreach (conf('groups', array()) as $groupname => $members) {
                    if (in_array($login, $members)) {
                        $groups[] = $groupname;
                    }
                }
                return 'successful';
            }
            return 'failed';
        }
        
        return 'not_found';
    }

    public function load_phpass()
    {
        return new PasswordHash(8, true);
    }

}