<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use MR\Kiss\Contracts\LegacyUser;

class User extends Authenticatable implements LegacyUser
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Retrieve business units where this user is a member (manager or normal user).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function memberOfBusinessUnits() {
        return $this->belongsToMany('App\BusinessUnit');
    }

    /**
     * Retrieve business units where this user is a manager of the business unit.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function managerOfBusinessUnits() {
        return $this->memberOfBusinessUnits()->wherePivot('role', 'manager');
    }

    /**
     * Retrieve business units where this user has a basic user role in the business
     * unit.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function userOfBusinessUnits() {
        return $this->memberOfBusinessUnits()->wherePivot('role', 'user');
    }

    //// LegacyUser Interface for MunkiReport

    /**
     * @todo
     * @return bool
     */
    public function isAdmin(): bool
    {
        return true;
    }

    /**
     * @todo
     * @return bool
     */
    public function isManager(): bool
    {
        return true;
    }

    /**
     * @todo
     * @return bool
     */
    public function isArchiver(): bool
    {
        return true;
    }

    /**
     * @todo
     * @return bool
     */
    public function canArchive(): bool
    {
        return true;
    }

    /**
     * @todo
     * @return bool
     */
    public function canAccessMachineGroup($id): bool
    {
        return true;
    }

    /**
     * @todo
     * @return array
     */
    public function machineGroups(): array
    {
        return [];
    }
}
