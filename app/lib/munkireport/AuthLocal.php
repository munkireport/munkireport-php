<?php

namespace munkireport\lib;
use \Exception;
use Hautelook\Phpass\PasswordHash;
use Symfony\Component\Yaml\Yaml;

class AuthLocal extends AbstractAuth
{
    private $mechanism, $config, $authStatus, $login, $users;

    public function __construct($config)
    {
        $this->config = $config;
        $this->mechanism = 'local';
        $this->users = [];
        $this->loadUsers();
    }
    
    public function login($login, $password)
    {
        $this->authStatus = 'not_found';
        $this->login = $login;
        
        if ($login){
            $storedPassword = $this->findHashForLogin($login);
            
            if ($storedPassword) {
                $t_hasher = $this->load_phpass();
                if ($t_hasher->CheckPassword($password, $storedPassword)) {
                    $this->authStatus = 'success';
                    return true;
                }
                $this->authStatus = 'failed';
            }
        }
        
        return false;
    }
    
    public function getAuthMechanism()
    {
        return $this->mechanism;
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
        $groups = [];
        foreach (conf('groups', array()) as $groupname => $members) {
            if (in_array($this->login, $members)) {
                $groups[] = $groupname;
            }
        }
        return $groups;
    }
    
    private function findHashForLogin($login)
    {
        return isset($this->users[$login]['password_hash']) ? $this->users[$login]['password_hash'] : false;
    }

    public function load_phpass()
    {
        return new PasswordHash(8, true);
    }
    
    private function isYaml($file)
    {
        return pathinfo($file, PATHINFO_EXTENSION) == 'yml';
    }
    
    private function fullPath($dir, $file)
    {
        return rtrim($dir, '/') . '/' . $file;
    }
    
    private function getName($file)
    {
        return pathinfo($file, PATHINFO_FILENAME);
    }
    
    private function loadUsers()
    {
        foreach($this->config['search_paths'] as $user_path){
          if(! is_dir($user_path)){
              continue;
          }
          
          foreach(scandir($user_path) AS $file)
          {
              $full_path = $this->fullPath($user_path, $file);
              if(! $this->isYaml($full_path)) 
              {
                  continue;
              }
              try {
                  $user_data = Yaml::parseFile($full_path);
                  if(isset($user_data['password_hash'])){
                      $this->users[$this->getName($full_path)] = $user_data;
                  }
              } catch (\Exception $e) {
                 // Do something
              }
          }
        }
    }

}