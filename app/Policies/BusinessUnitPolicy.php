<?php

namespace App\Policies;

use App\BusinessUnit;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BusinessUnitPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\BusinessUnit  $businessUnit
     * @return mixed
     */
    public function update(User $user, BusinessUnit $businessUnit)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\BusinessUnit  $businessUnit
     * @return mixed
     */
    public function delete(User $user, BusinessUnit $businessUnit)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\BusinessUnit  $businessUnit
     * @return mixed
     */
    public function restore(User $user, BusinessUnit $businessUnit)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\BusinessUnit  $businessUnit
     * @return mixed
     */
    public function forceDelete(User $user, BusinessUnit $businessUnit)
    {
        return $user->isAdmin();
    }
}
