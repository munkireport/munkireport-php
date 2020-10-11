<?php

namespace App\Handlers;

use App\User as EloquentUser;
use Adldap\Models\User as LdapUser;
use Illuminate\Support\Facades\Log;

class LdapAttributeHandler
{
    /**
     * Synchronizes ldap attributes to the specified model.
     *
     * @param LdapUser     $ldapUser
     * @param EloquentUser $eloquentUser
     *
     * @return void
     */
    public function handle(LdapUser $ldapUser, EloquentUser $eloquentUser)
    {
        Log::debug("Syncing LDAP attributes");
        $eloquentUser->name = $ldapUser->getCommonName();
        $eloquentUser->email = $ldapUser->getUserPrincipalName();
    }
}

