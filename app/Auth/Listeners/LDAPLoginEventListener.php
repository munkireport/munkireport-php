<?php

namespace App\Auth\Listeners;

use \Adldap\Laravel\Events\AuthenticationSuccessful;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LDAPLoginEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AuthenticationSuccessful  $event
     * @return void
     */
    public function handle(AuthenticationSuccessful $event)
    {
        Log::info("authentication was successful");
        $groupNames = $event->user->getGroupNames();
        Log::debug("group membership: ", $groupNames);
        session()->replace(['groups' => $groupNames]);
    }
}
