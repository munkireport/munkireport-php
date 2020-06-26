<?php


namespace App\Auth\Listeners;

use Aacotroneo\Saml2\Events\Saml2LoginEvent;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Event Listener for SAML2 Logins
 *
 * As per the laravel-adldap2 package, listens for SAML2 Login events and returns or creates the corresponding
 * local user and session.
 *
 * @package App\Auth\Listeners
 */
class Saml2LoginEventListener
{
    public function handle(Saml2LoginEvent $event)
    {
        $messageId = $event->getSaml2Auth()->getLastMessageId();
        // Add your own code preventing reuse of a $messageId to stop replay attacks
        Log::debug("Not checking SAML2 Message ID for replays, this will be fixed in a future version. Message ID: ${messageId}");

        $user = $event->getSaml2User();
        $userData = [
            'id' => $user->getUserId(),
            'attributes' => $user->getAttributes(),
            'assertion' => $user->getRawSamlAssertion()
        ];

        $providerName = config('auth.guards.web.provider');
        $userProvider = Auth::createUserProvider($providerName);
        if (!$userProvider) {
            Log::error("Unable to instantiate user provider for SAML2 login: `${providerName}`. Your auth user provider configuration may be wrong, or database connection details incorrect.");
            return;
        }

        $user = $userProvider->retrieveByCredentials(['email' => $userData['id']]);
        if (!$user) {
            $user = new User();
            $user->email = $userData['id'];
            $user->name = $userData['id'];
            $user->password = Hash::make(Str::random(64)); // The user is never intended to use this
            $user->saveOrFail();
        }

        $idpName = $event->getSaml2Idp();
        Log::info("${userData['id']} logged in via SAML2 IdP `${idpName}`");
        Auth::login($user);
    }
}
