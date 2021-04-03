<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use MR\Kiss\Contracts\LegacyUser;

use MR\BusinessUnit as LegacyBusinessUnit;
use MR\MachineGroup as LegacyMachineGroup;

use munkireport\models\Machine_group;

class User extends Authenticatable implements LegacyUser, HasLocalePreference
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
     * Returns whether this user is an Administrator or not (for backwards compatibility).
     *
     * The role of a user is decided at login time by the LoginRoleDecider Auth Listener and then saved to
     * the database.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * @todo
     * @return bool
     */
    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    /**
     * @todo
     * @return bool
     */
    public function isArchiver(): bool
    {
        return $this->role === 'archiver';
    }

    /**
     * Returns whether this user can archive machines or not (for backwards compatibility).
     *
     * Regardless of whether Business Units are disabled/enabled:
     * - admins, managers, and archivers may archive, but regular users may not.
     *
     * @return bool
     */
    public function canArchive(): bool
    {
        return in_array($this->role, ['admin', 'manager', 'archiver']);
    }

    /**
     * Backwards compatible function to check whether this User has access to a specific machine_group
     * by ID.
     *
     * @return bool
     */
    public function canAccessMachineGroup($id): bool
    {
        if ($this->role === 'admin') return true; // Admins always have access to everything

        return in_array($id, $this->machineGroups());
    }

    /**
     * @todo
     * @return array
     */
    public function machineGroups(): array
    {
        return [];
    }

    /**
     * Retrieve a list of contact methods associated with this User.
     *
     * @return HasMany
     */
    public function contactMethods(): HasMany
    {
        return $this->hasMany('App\UserContactMethod', 'user_id', 'id');
    }

    /**
     * Retrieve a list of contact methods associated with this User for a specific channel type.
     *
     * The channel name corresponds to the Laravel notifications channel name.
     *
     * @param string $channel A channel name corresponding to the Laravel notifications channel name, eg. `mail`.
     * @return HasMany
     */
    public function contactMethodsForChannel(string $channel): HasMany
    {
        return $this->hasMany('App\UserContactMethod', 'user_id', 'id')
            ->where('channel', $channel);
    }

    /**
     * Get the user's preferred locale.
     *
     * Implemented for `Illuminate\Contracts\Translation\HasLocalePreference` to send notifications in the
     * user's language if possible.
     *
     * @return string
     */
    public function preferredLocale(): string
    {
        return $this->locale;
    }
}
