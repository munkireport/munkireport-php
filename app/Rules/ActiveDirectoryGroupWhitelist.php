<?php
namespace App\Rules;

use Adldap\Laravel\Validation\Rules\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Group-based Active Directory authentication whitelist.
 *
 * This validation rule implements backwards-compatible functionality for the `AUTH_AD_ALLOWED_GROUPS` configuration
 * variable in MunkiReport.
 *
 * @package App\Rules
 */
class ActiveDirectoryGroupWhitelist extends Rule
{
    /**
     * @inheritDoc
     */
    public function isValid()
    {
        $allowedGroups = config('AUTH_AD_ALLOWED_GROUPS', '');
        if (!empty($allowedGroups)) {
            $allowedGroups = explode(',', $allowedGroups);
            $groupRecursive = (bool) config('AUTH_AD_RECURSIVE_GROUPSEARCH', false);

            foreach ($allowedGroups as $allowedGroup) {
                if ($this->user->inGroup($allowedGroup, $groupRecursive)) {
                    return true;
                }
            }

            Log::debug('Attempted login from user not in allowed groups list', [
                'allowedGroups' => $allowedGroups,
            ]);

            return false;
        } else {
            // Without a configured group list, we assume the whitelist is not in effect.
            return true;
        }
    }
}
