<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class Oauth2Controller extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Initiate an OpenID Connect authentication flow by redirecting to the specified provider.
     *
     * The provider name must match the name of a Socialite provider, declared in config/services.php (for now).
     * We reserve the right to change the string to a named set of configurations.
     *
     * @param string $provider The socialite driver name to invoke a redirect with.
     * @return RedirectResponse|void Redirection to the Identity Provider, or void if an error occurred.
     * @throws BadRequestException when an invalid provider name is given.
     */
    public function redirect(string $provider)
    {
        $services = config('services');
        if (!Arr::has($services, $provider)) {
            return abort(400, "Invalid provider requested or unconfigured");
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Receive a callback from an OAuth2 authorization code flow sign-on.
     *
     * The provider name must match the name of a Socialite provider, declared in config/services.php (for now).
     * We reserve the right to change the string to a named set of configurations.
     *
     * @param string $provider The socialite driver that initiated the oauth2 flow.
     * @return RedirectResponse Redirection to the configured home page (if successful)
     */
    public function callback(string $provider)
    {
        $services = config('services');
        if (!Arr::has($services, $provider)) {
            return abort(400, "Invalid provider requested or unconfigured");
        }

        $user = Socialite::driver('azure')->user();

        $databaseUser = User::where('email', $user->getEmail())->first();
        if ($databaseUser) {
            $databaseUser->update([
                'email' => $user->getEmail(),
                'display_name' => $user->getName(),
                'source' => $provider,
            ]);
        } else {
            $databaseUser = User::create([
                'name' => $user->getName(),
                'objectguid' => $user->getId(),
                'email' => $user->getEmail(),
                'display_name' => $user->getName(),
                'password' => Str::random(40),
                'source' => $provider,
            ]);
        }

        Auth::login($databaseUser);
        return redirect($this->redirectTo);
    }
}
