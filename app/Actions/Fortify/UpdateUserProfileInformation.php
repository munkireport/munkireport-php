<?php

namespace App\Actions\Fortify;

use App\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use munkireport\lib\Themes;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, string>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
            'locale' => [
                'string'
            ],
            'theme' => ['nullable', 'string']
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'name' => $input['name'],
                'email' => $input['email'],
                'locale' => $input['locale'] ?? 'en',
                'theme' => $input['theme']
            ])->save();
        }

        if (isset($input['theme'])) {
            $themeObj = new Themes();
            if(in_array($input['theme'], $themeObj->get_list()))
            session()->put('theme', $input['theme']);
        }

        // TODO:
        //             'locale' => $request->getLocale(),
        //            'current_theme' => $request->session()->get('theme', config('_munkireport.default_theme')),
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
            'locale' => $input['locale'],
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
