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
 * @subpackage Groups
 * @author Scott Barnett, Richard Hyland
 * @copyright (c) 2006-2012 Scott Barnett, Richard Hyland
 * @license http://www.gnu.org/licenses/old-licenses/lgpl-2.1.html LGPLv2.1
 * @revision $Revision: 97 $
 * @version 4.0.4
 * @link http://adldap.sourceforge.net/
 */
require_once(dirname(__FILE__) . '/../adLDAP.php');
require_once(dirname(__FILE__) . '/../collections/adLDAPGroupCollection.php');

/**
* GROUP FUNCTIONS
*/
class adLDAPGroups {
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
    * Return a list of groups in a group
    * 
    * @param string $group The group to query
    * @param bool $recursive Recursively get groups
    * @return array
    */
    public function inGroup($group, $recursive = NULL)
    {
        if (!$this->adldap->getLdapBind()){ return false; }
        if ($recursive === NULL){ $recursive = $this->adldap->getRecursiveGroups(); } // Use the default option if they haven't set it 
        
        // Search the directory for the members of a group
        $info = $this->info($group, array("member","cn"));
        $groups = $info[0]["member"];
        if (!is_array($groups)) {
            return false;   
        }
 
        $groupArray = array();

        for ($i=0; $i<$groups["count"]; $i++){ 
             $filter = "(&(objectCategory=group)(distinguishedName=" . $this->adldap->utilities()->ldapSlashes($groups[$i]) . "))";
             $fields = array("samaccountname", "distinguishedname", "objectClass");
             $sr = ldap_search($this->adldap->getLdapConnection(), $this->adldap->getBaseDn(), $filter, $fields);
             $entries = ldap_get_entries($this->adldap->getLdapConnection(), $sr);

             // not a person, look for a group  
             if ($entries['count'] == 0 && $recursive == true) {  
                $filter = "(&(objectCategory=group)(distinguishedName=" . $this->adldap->utilities()->ldapSlashes($groups[$i]) . "))";  
                $fields = array("distinguishedname");  
                $sr = ldap_search($this->adldap->getLdapConnection(), $this->adldap->getBaseDn(), $filter, $fields);  
                $entries = ldap_get_entries($this->adldap->getLdapConnection(), $sr);  
                if (!isset($entries[0]['distinguishedname'][0])) {
                    continue;  
                }
                $subGroups = $this->inGroup($entries[0]['distinguishedname'][0], $recursive);  
                if (is_array($subGroups)) {
                    $groupArray = array_merge($groupArray, $subGroups); 
                    $groupArray = array_unique($groupArray);  
                }
                continue;  
             } 

             $groupArray[] = $entries[0]['distinguishedname'][0];
        }
        return $groupArray;
    }
    
    /**
    * Return a complete list of "groups in groups"
    * 
    * @param string $group The group to get the list from
    * @return array
    */
    public function recursiveGroups($group)
    {
        if ($group === NULL) { return false; }

        $stack = array(); 
        $processed = array(); 
        $retGroups = array(); 
     
        array_push($stack, $group); // Initial Group to Start with 
        while (count($stack) > 0) {
            $parent = array_pop($stack);
            array_push($processed, $parent);
            
            $info = $this->info($parent, array("memberof"));
            
            if (isset($info[0]["memberof"]) && is_array($info[0]["memberof"])) {
                $groups = $info[0]["memberof"]; 
                if ($groups) {
                    $groupNames = $this->adldap->utilities()->niceNames($groups);  
                    $retGroups = array_merge($retGroups, $groupNames); //final groups to return
                    foreach ($groupNames as $id => $groupName) { 
                        if (!in_array($groupName, $processed)) {
                            array_push($stack, $groupName);
                        }
                    }
                }
            }
        }
        
        return $retGroups;
    }

}
?>
