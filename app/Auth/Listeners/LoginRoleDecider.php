<?php


namespace App\Auth\Listeners;

use App\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;
use munkireport\models\Business_unit;
use Compatibility\BusinessUnit as LegacyBusinessUnit;
use Compatibility\MachineGroup as LegacyMachineGroup;

/**
 * LoginRoleDecider listens for successful logins and then decides, using the same rules that AuthHandler::setSessionProps
 * used to have, which role(s) the user should get for this session.
 *
 * @package App\Auth\Listeners
 */
class LoginRoleDecider
{
    /**
     * Calculate whether authenticated user with userPrincipal name + group membership is part of the given role array.
     *
     * TODO: original munkireport precedence rules meant that wildcards took a lower precedence than specific user or
     * group names. Check this.
     *
     * @param string $userPrincipal The user principal, such as the email address of the user logging in.
     * @param array $groups The list of groups that was given to us from the authentication provider.
     * @param string $role The role name we are evaluating.
     * @param array $members The list of users and groups who are a member of the role given.
     * @param string $email The email address of the user, provided when the user principal is not an email address.
     * @return array [bool, string] corresponding to isMember (true/false), and reason string.
     */
    protected function isMember(string $userPrincipal, array $groups, string $role, array $members, string $email): array {
        foreach ($members as $userOrGroupName) {
            if (strpos($userOrGroupName, '@') === 0) { // It's a group
                if (in_array(substr($userOrGroupName, 1), $groups)) {
                    return [true, "member of ${userOrGroupName}"];
                }
            } else {
                if ($userOrGroupName == $userPrincipal) {
                    return [true, "${userOrGroupName} in ${role} role array"];
                }

                if ($userOrGroupName == $email) {
                    return [true, "${email} in ${role} role array"];
                }
            }
        }

        // Wildcard matches all users
        if (in_array('*', $members)) {
            return [true, "Matched on wildcard (*) in ${role}"];
        }

        return [false, "not a member of ${role}"];
    }

    /**
     * Calculate whether authenticated user with userPrincipal name is part of a business unit.
     *
     * @param string $userPrincipal The user principal, such as the email address of the user logging in.
     * @param array $groups The list of groups that was given to us from the authentication provider.
     * @return array [bool, array] corresponding to isMember (true/false), and business unit data.
     */
    private function belongsToBusinessUnit(string $userPrincipal, array $groups)
    {
        $businessUnitData = [
            'role' => 'nobody',
            'roleWhy' => 'Default role for Business Units',
            'businessUnitId' => 0,
        ];
        $foundInBusinessUnit = false;

        $businessUnitMemberships = LegacyBusinessUnit::members()->where('value', $userPrincipal);
        $bu = $businessUnitMemberships->first();
        
        if ($bu) {
            $businessUnitData['role'] = $bu->property; // manager, user
            $businessUnitData['roleWhy'] = $userPrincipal.' found in Business Unit '. $bu->unitid;
            $businessUnitData['businessUnitId'] = $bu->unitid;
            $foundInBusinessUnit = true;
        } else {
            // Lookup groups in Business Units
            $fnPrefix = function($name) {
                return "@".$name;
            };

            $groupsPrefixed = array_map($fnPrefix, $groups);
            $bu = LegacyBusinessUnit::members()
                ->whereIn('value', $groupsPrefixed)
                ->first();

            // Multiple BU membership is not possible in LegacyBusinessUnit, but the precedence is undefined, I think - mosen.
            // TODO: Establish precedence rule

            if ($bu) {
                $businessUnitData['role'] = $bu->property; // manager, user
                $businessUnitData['roleWhy'] = 'Group "'. $group . '" found in Business Unit '. $bu->unitid;
                $businessUnitData['businessUnitId'] = $bu->unitid;
                $foundInBusinessUnit = true;
            }
        }

        return [$foundInBusinessUnit, $businessUnitData];
    }

    public function handle(Login $event) {
        Log::info('evaluating role memberships');

        // It is assumed that the auth provider has set a list of groups in the session by this point.
        $groups = session()->get('groups', []);
        Log::debug("group list received from authentication provider: ", $groups);

        $userId = $event->user->getAuthIdentifier();
        $findByAttribute = $event->user->getAuthIdentifierName();
        $user = User::findOrFail($userId);
        $userName = $user->name;
        $email = $user->email;


        $setRole = 'user';
        $roleWhy = 'Default role';

        $roles = config('_munkireport.roles', []);

        foreach ($roles as $role => $members) {
            list($isMember, $roleWhy) = $this->isMember($userName, $groups, $role, $members, $email);
            if ($isMember) {
                $setRole = $role;
                break;
            }
        }

        // Check if Business Units are enabled in the config file
        if ($setRole != 'admin' && config('_munkireport.enable_business_units', false)) {
            list($isMember, $businessUnitData) = $this->belongsToBusinessUnit($userName, $groups);
            if ($isMember) {
                $setRole = $businessUnitData['role'];
                $roleWhy = $businessUnitData['roleWhy'];
                session()->put('business_unit', $businessUnitData['businessUnitId']);
            }
        }

        $user->role = $setRole;
        $user->update();
        Log::info("${userName} is member of ${setRole}, because ${roleWhy}.");

    }
}
