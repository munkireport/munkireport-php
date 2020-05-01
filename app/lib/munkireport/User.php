<?php

namespace munkireport\lib;

/**
* User class
*
* Store and retrieve information about the logged in user
*
*/
class User
{
    private $conf, $session;

    public function __construct($conf = [])
    {
        $this->conf = $conf;
        $this->session = $_SESSION;
    }

    public function isAdmin()
    {
        return $this->_getRole() == 'admin';
    }

    public function isManager()
    {
        return $this->_getRole() == 'manager';
    }

    public function isArchiver()
    {
        return $this->_getRole() == 'archiver';
    }

    public function canArchive()
    {
        return $this->isAdmin() || $this->isManager() || $this->isArchiver();
    }

    public function canAccessMachineGroup($id)
    {
        if ($this->isAdmin()) {
            return true;
        }
    
        return in_array($id, $this->machineGroups());
    }

    public function machineGroups()
    {
        return $this->session['machine_groups'] ?? [];
    }

    private function _getRole()
    {
        return $this->session['role'] ?? 'nobody';
    }
}
