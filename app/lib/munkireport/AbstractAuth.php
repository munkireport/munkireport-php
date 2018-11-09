<?php

namespace munkireport\lib;

// Declare the interface 'AuthInterface'
abstract class AbstractAuth
{
    abstract public function login($login, $password);
    abstract public function getAuthMechanism();
    abstract public function getAuthStatus();
    abstract public function getUser();
    abstract public function getGroups();

    protected function authorizeUserAndGroups($auth_config, $auth_data)
    {
        $checkUser = !empty($auth_config['mr_allowed_users']);
        $checkGroups = !empty($auth_config['mr_allowed_groups']);

        if( ! $checkUser && ! $checkGroups){
            return true;
        }

        if ($checkUser) {
            $admin_users = $this->valueToArray($auth_config['mr_allowed_users']);
            if (in_array(strtolower($auth_data['user']), array_map('strtolower', $admin_users))) {
                return true;
            }
        }
        // Check user against group list
        if ($checkGroups) {
        // Set mr_allowed_groups to array
            $admin_groups = $this->valueToArray($auth_config['mr_allowed_groups']);
            foreach ($auth_data['groups'] as $group) {
                if (in_array($group, $admin_groups)) {
                    return true;
                }
            }
        }//end group list check

        return false;
    }

    /**
     * Convert value to array or keep Array
     *
     * @param mixed $value string or array
     * @return return array
     */
    private function valueToArray($value='')
    {
        return is_array($value) ? $value : [$value];
    }

    /**
     * Remove MunkiReport specific items from config array
     *
     * adldap2 trips over extra items in config array
     *
     * @param type $config Config array
     * @return return array
     */
    public function stripMunkireportItemsFromConfig($config)
    {
        foreach(['mr_allowed_users', 'mr_allowed_groups', 'mr_recursive_groupsearch'] as $item){
            unset($config[$item]);
        }
        return $config;
    }
}
