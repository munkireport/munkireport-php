<?php


namespace App\Auth;


use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;

class MultiAuthGuard implements Guard
{
    public static $supportedMethods = [
        "LOCAL", "NOAUTH", "SAML", "AD"
    ];

    protected $provider;
    protected $authMethods = [];

    private function validateMethods(array $authMethods) {

    }

    public function __construct(UserProvider $provider) {
        $this->provider = $provider;
        $this->authMethods = config('');
    }


    /**
     * @inheritDoc
     */
    public function check()
    {
        // TODO: Implement check() method.
    }

    /**
     * @inheritDoc
     */
    public function guest()
    {
        // TODO: Implement guest() method.
    }

    /**
     * @inheritDoc
     */
    public function user()
    {
        // TODO: Implement user() method.
    }

    /**
     * @inheritDoc
     */
    public function id()
    {
        // TODO: Implement id() method.
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
}
