<?php


namespace App\Notifications;

use App\User;
use Illuminate\Notifications\Notifiable;

/**
 * ContactMethodNotifiable implements the via() method for Notifications so that only the enabled and/or available
 * contact methods for the current user are used for Notification delivery.
 *
 * @package App\Notifications
 */
trait ContactMethodNotifiable
{
    /**
     * Get the notification's delivery channels.
     *
     * For a User notifiable it will be a list of each registered contact method that is ALSO enabled within the
     * system for notification delivery.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via(Notifiable $notifiable): array
    {
        if ($notifiable instanceof User) {
            return $notifiable->contactMethods()
                              ->get('channel')
                              ->toArray();
        } else {
            return [];
        }
    }
}
