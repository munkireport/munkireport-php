<?php
/**
 * PHP LDAP CLASS FOR MANIPULATING ACTIVE DIRECTORY 
 * Version 4.0.4
 * 
 * PHP Version 5 with SSL and LDAP support
 * 
 * Written by Scott Barnett, Richard Hyland
 *   email: scott@wiggumworld.com, adldap@richardhyland.com
 *   http://adldap.sourceforge.net/
 * 
 * Copyright (c) 2006-2012 Scott Barnett, Richard Hyland
 * 
 * We'd appreciate any improvements or additions to be submitted back
 * to benefit the entire community :)
 * 
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License.
 * 
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 * 
 * @category ToolsAndUtilities
 * @package adLDAP
 * @subpackage User
 * @author Scott Barnett, Richard Hyland
 * @copyright (c) 2006-2012 Scott Barnett, Richard Hyland
 * @license http://www.gnu.org/licenses/old-licenses/lgpl-2.1.html LGPLv2.1
 * @revision $Revision: 97 $
 * @version 4.0.4
 * @link http://adldap.sourceforge.net/
 */
require_once(dirname(__FILE__) . '/../adLDAP.php');
require_once(dirname(__FILE__) . '/../collections/adLDAPUserCollection.php');

/**
* USER FUNCTIONS
*/
class adLDAPUsers {
    /**
    * The current adLDAP connection via dependency injection
    * 
    * @var adLDAP
    */
    protected $adldap;
    
    public function __construct(adLDAP $adldap) {
        $this->adldap = $adldap;
    }
    
    /**
    * Validate a user's login credentials
    * 
    * @param string $username A user's AD username
    * @param string $password A user's AD password
    * @param bool optional $prevent_rebind
    * @return bool
    */
    public function authenticate($username, $password, $preventRebind = false) {
        return $this->adldap->authenticate($username, $password, $preventRebind);
    }

    /**
    * Groups the user is a member of
    * 
    * @param string $username The username to query
    * @param bool $recursive Recursive list of groups
    * @param bool $isGUID Is the username passed a GUID or a samAccountName
    * @return array
    */
    public function groups($username, $recursive = NULL, $isGUID = false)
    {
        if ($username === NULL) { return false; }
        if ($recursive === NULL) { $recursive = $this->adldap->getRecursiveGroups(); } // Use the default option if they haven't set it
        if (!$this->adldap->getLdapBind()) { return false; }
        
        // Search the directory for their information
        $info = @$this->info($username, array("memberof", "primarygroupid"), $isGUID);
        $groups = $this->adldap->utilities()->niceNames($info[0]["memberof"]); // Presuming the entry returned is our guy (unique usernames)

        if ($recursive === true){
            foreach ($groups as $id => $groupName){
                $extraGroups = $this->adldap->group()->recursiveGroups($groupName);
                $groups = array_merge($groups, $extraGroups);
            }
        }
        
        return $groups;
    }

    /**
    * Determine if a user is in a specific group
    * 
    * @param string $username The username to query
    * @param string $group The name of the group to check against
    * @param bool $recursive Check groups recursively
    * @param bool $isGUID Is the username passed a GUID or a samAccountName
    * @return bool
    */
    public function inGroup($username, $group, $recursive = NULL, $isGUID = false)
    {
        if ($username === NULL) { return false; }
        if ($group === NULL) { return false; }
        if (!$this->adldap->getLdapBind()) { return false; }
        if ($recursive === NULL) { $recursive = $this->adldap->getRecursiveGroups(); } // Use the default option if they haven't set it
        
        // Get a list of the groups
        $groups = $this->groups($username, $recursive, $isGUID);
        
        // Return true if the specified group is in the group list
        if (in_array($group, $groups)) { 
            return true; 
        }

        return false;
    }

    /**
    * Encode a password for transmission over LDAP
    *
    * @param string $password The password to encode
    * @return string
    */
    public function encodePassword($password)
    {
        $password="\"".$password."\"";
        $encoded="";
        for ($i=0; $i <strlen($password); $i++){ $encoded.="{$password{$i}}\000"; }
        return $encoded;
    }
     
    /**
    * Obtain the user's distinguished name based on their userid 
    * 
    * 
    * @param string $username The username
    * @param bool $isGUID Is the username passed a GUID or a samAccountName
    * @return string
    */
    public function dn($username, $isGUID=false)
    {
        $user = $this->info($username, array("cn"), $isGUID);
        if ($user[0]["dn"] === NULL) { 
            return false; 
        }
        $userDn = $user[0]["dn"];
        return $userDn;
    }

    /**
    * Converts a username (samAccountName) to a GUID
    * 
    * @param string $username The username to query
    * @return string
    */
    public function usernameToGuid($username) 
    {
        if (!$this->adldap->getLdapBind()){ return false; }
        if ($username === null){ return "Missing compulsory field [username]"; }
        
        $filter = "samaccountname=" . $username; 
        $fields = array("objectGUID"); 
        $sr = @ldap_search($this->adldap->getLdapConnection(), $this->adldap->getBaseDn(), $filter, $fields); 
        if (ldap_count_entries($this->adldap->getLdapConnection(), $sr) > 0) { 
            $entry = @ldap_first_entry($this->adldap->getLdapConnection(), $sr); 
            $guid = @ldap_get_values_len($this->adldap->getLdapConnection(), $entry, 'objectGUID'); 
            $strGUID = $this->adldap->utilities()->binaryToText($guid[0]);          
            return $strGUID; 
        }
        return false; 
    }
    
}
?>
