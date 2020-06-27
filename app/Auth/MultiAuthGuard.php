<?php


namespace App\Auth;


use App\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Auth\UserProvider;

class MultiAuthGuard implements StatefulGuard
{
    public static $supportedMethods = [
        "LOCAL", "NOAUTH", "SAML", "AD"
    ];

    protected $provider;
    protected $authMethods;

    private function validateMethods(array $authMethods) {

    }

    public function __construct(UserProvider $provider, array $authMethods = ['LOCAL']) {
        $this->provider = $provider;
        $this->authMethods = $authMethods;
    }

    //// GUARD

    /**
     * @inheritDoc
     */
    public function check()
    {
        if (in_array('NOAUTH', $this->authMethods)) {
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function guest()
    {
        if (in_array('NOAUTH', $this->authMethods)) {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function user()
    {
        if (in_array('NOAUTH', $this->authMethods)) {
            return new User(['name' => 'unauthenticated']);
        }
    }

    /**
     * @inheritDoc
     */
    public function id()
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function validate(array $credentials = [])
    {
        // TODO: Implement validate() method.
    }

    /**
     * @inheritDoc
     */
    public function setUser(Authenticatable $user)
    {
        // TODO: Implement setUser() method.
    }

    //// STATEFULGUARD

    public function attempt(array $credentials = [], $remember = false)
    {
        // TODO: Implement attempt() method.
    }

    public function once(array $credentials = [])
    {
        // TODO: Implement once() method.
    }

    public function login(Authenticatable $user, $remember = false)
    {
        // TODO: Implement login() method.
    }

    public function loginUsingId($id, $remember = false)
    {
        // TODO: Implement loginUsingId() method.
    }

    public function onceUsingId($id)
    {
        // TODO: Implement onceUsingId() method.
    }

    public function viaRemember()
    {
        // TODO: Implement viaRemember() method.
    }

    public function logout()
    {

    }
}
