<?php
namespace Mr\Scopes;

use Adldap\Query\Builder;
use Adldap\Laravel\Scopes\ScopeInterface;

/**
 * This scope is used with ADldap in order to restrict logins to members of specify Security Groups within Active
 * Directory.
 * 
 * @package Mr\Scopes
 */
class ADGroupMembership implements ScopeInterface
{
    /**
     * Apply the scope to a given Adldap query builder.
     *
     * @param Builder $builder
     *
     * @return void
     */
    public function apply(Builder $builder)
    {
        $ADGroup = config('munkireport.ad_login_group', null);

        if ($ADGroup) {
            $builder->whereMemberOf($ADGroup);
        }
    }
}