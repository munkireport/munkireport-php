<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Compatibility\Kiss\Contracts\LegacyUser;

use Compatibility\BusinessUnit as LegacyBusinessUnit;
use Compatibility\MachineGroup as LegacyMachineGroup;

use munkireport\models\Machine_group;

class User extends Authenticatable implements LegacyUser, HasLocalePreference
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password', 'source', 'objectguid'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    //// Business Units V1

    /**
     * Retrieve legacy business unit membership rows where this user is a member
     * (via their username) having a role of manager, archiver, or user.
     * @return mixed
     */
    public function memberOfLegacyBusinessUnits() {
        return LegacyBusinessUnit::members()
            ->where('value', '=', $this->name);
    }

    //// Business Units V2 (Alpha)

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

    /**
     * Retrieve business units where this user holds the given role name.
     *
     * @param string $role The role name to filter by, only business units will be returned if the user holds a role in them.
     */
    public function businessUnitsWithRole(string $role): BelongsToMany {
        return $this->memberOfBusinessUnits()->wherePivot('role', $role);
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
        return $this->role == 'admin';
    }

    /**
     * @todo
     * @return bool
     */
    public function isManager(): bool
    {
        return $this->role == 'manager';
    }

    /**
     * @todo
     * @return bool
     */
    public function isArchiver(): bool
    {
        return $this->role == 'archiver';
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
        if ($this->role == 'admin') return true; // Admins always have access to everything

        return in_array($id, $this->machineGroups());
    }

    /**
     * Backwards compatible function to list machine groups that the user has access to.
     *
     * - If you are an admin this does not apply and you get all machine groups
     * - MunkiReport 5.6.5 sets this information on the session when you log in
     * - Backwards compatibility for session-based machine groups is provided by App\Auth\Listeners\MachineGroupMembership
     *
     * This was moved from a session value to a database query because machine groups need to be queried for authz decisions in
     * stateless access such as via REST API.
     *
     * @return array
     */
    public function machineGroups(): array
    {
        //return session('machine_groups') ?? [];
        if (!config('_munkireport.enable_business_units', false) || $this->role === 'admin') {
            // Can access all defined groups (from machine_group)
            // and used groups (from reportdata)
            $mg = new Machine_group;
            $reportedMachineGroups = ReportData::select('machine_group')
                ->groupBy('machine_group')
                ->get()
                ->pluck('machine_group')
                ->toArray();
            $reportedMachineGroups = $reportedMachineGroups ? $reportedMachineGroups : [0];
            $machineGroups = array_unique(array_merge($reportedMachineGroups, $mg->get_group_ids()));
        } else {
            $businessUnitMembership = LegacyBusinessUnit::members()->where('value', $this->name)->first();
            $machineGroups = LegacyBusinessUnit::where('unitid', $businessUnitMembership->unitid)
                ->where('property', 'machine_group')
                ->get()
                ->pluck('value')
                ->toArray();
        }

        return $machineGroups;
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
