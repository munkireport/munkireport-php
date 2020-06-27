<?php


namespace App\Rules;

use Adldap\Laravel\Validation\Rules\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * User-based Active Directory authentication whitelist.
 *
 * This validation rule implements backwards-compatible functionality for the `AUTH_AD_ALLOWED_USERS` configuration
 * variable in MunkiReport.
 *
 * @package App\Rules
 */
class ActiveDirectoryUserWhitelist extends Rule
{
    /**
     * @inheritDoc
     */
    public function isValid()
    {
        $allowedUsers = config('AUTH_AD_ALLOWED_USERS', '');
        if (!empty($allowedUsers)) {
            $allowedUsers = explode(',', $allowedUsers);

            foreach ($allowedUsers as $allowedUser) {
                if ($this->user->getAuthIdentifier() === $allowedUser) {
                    return true;
                }
            }

            Log::debug('Attempted login from user not in allowed users list: ' . $this->user->getAuthIdentifier(), [
                'allowedUsers' => $allowedUsers,
            ]);

            return false;
        } else {
            // Without a configured user list, we assume the whitelist is not in effect.
            return true;
        }
    }
}
