<?php

namespace App\Providers;

use Aacotroneo\Saml2\Events\Saml2LoginEvent;
use App\Auth\Listeners\LoginRoleDecider;
use App\Auth\Listeners\MachineGroupMembership;
use App\Auth\Listeners\Saml2LoginEventListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'Aacotroneo\Saml2\Events\Saml2LoginEvent' => [
            Saml2LoginEventListener::class,  // Required to create shadow users at login time
        ],
        'Illuminate\Auth\Events\Login' => [
            LoginRoleDecider::class,  // based on LDAPLoginEventListener or Saml2LoginEventListener decides which roles
                                      // should apply.
            MachineGroupMembership::class,  // decides which machinegroup memberships should apply.
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

    }
}
