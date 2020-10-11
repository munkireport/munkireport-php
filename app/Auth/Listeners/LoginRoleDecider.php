<?php


namespace App\Auth\Listeners;

use App\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;
use munkireport\models\Business_unit;

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
     * @return array [bool, string] corresponding to isMember (true/false), and reason string.
     */
    protected function isMember(string $userPrincipal, array $groups, string $role, array $members): array {
        foreach ($members as $userOrGroupName) {
            if (strpos($userOrGroupName, '@') === 0) { // It's a group
                if (in_array(substr($member, 1), $groups)) {
                    return [true, "member of ${member}"];
                }
            } else {
                if ($userOrGroupName == $userPrincipal) {
                    return [true, "${member} in ${role} role array"];
                }
            }
        }

        // Wildcard matches all users
        if (in_array('*', $members)) {
            return [true, "Matched on wildcard (*) in ${role}"];
        }

        return [false, "not a member of ${role}"];
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


        $setRole = 'user';
        $roleWhy = 'Default role';

        $roles = config('_munkireport.roles', []);
        foreach ($roles as $role => $members) {
            list($isMember, $roleWhy) = $this->isMember($userName, $groups, $role, $members);
            if ($isMember) {
                $setRole = $role;
                break;
            }
        }

        Log::info("${userName} is member of ${setRole}, because ${roleWhy}.");

        // Check if Business Units are enabled in the config file
        $bu_enabled = config('_munkireport.enable_business_units', false);

        // Check if user is global admin
//        if ($_SESSION['auth'] == 'noauth' or $_SESSION['role'] == 'admin') {
//            unset($_SESSION['business_unit']);
//        } elseif (! $bu_enabled) {
//            // Regular user w/o business units enabled
//            unset($_SESSION['business_unit']);
//        } elseif ($bu_enabled) {
//            // Authorized user, not in business unit
//            $_SESSION['role'] = 'nobody';
//            $_SESSION['role_why'] = 'Default role for Business Units';
//            $_SESSION['business_unit'] = 0;
//
//            // Lookup user in business units
//            $bu = Business_unit::whereIn('property', ['manager', 'archiver', 'user'])
//                ->where('value', $_SESSION['user'])
//                ->first();
//
//            if ($bu) {
//                $_SESSION['role'] = $bu->property; // manager, user
//                $_SESSION['role_why'] = $_SESSION['user'].' found in Business Unit '. $bu->unitid;
//                $_SESSION['business_unit'] = $bu->unitid;
//            } else {
//                // Lookup groups in Business Units
//                foreach ($_SESSION['groups'] as $group) {
//                    $bu = Business_unit::whereIn('property', ['manager', 'archiver', 'user'])
//                        ->where('value', '@' . $group)
//                        ->first();
//
//                    if ($bu) {
//                        $_SESSION['role'] = $bu->property; // manager, user
//                        $_SESSION['role_why'] = 'Group "'. $group . '" found in Business Unit '. $bu->unitid;
//                        $_SESSION['business_unit'] = $bu->unitid;
//                        break;
//                    }
//                }
//            }
//        }
    }
}
