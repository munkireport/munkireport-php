<?php


namespace App\Notifications;

use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;

/**
 * Class GlobalNotifiable
 *
 * The GlobalNotifiable class represents a Notification recipient for indirect, broadcast type recipients, such as:
 *
 * - Slack channels
 * - Teams channels
 * - Anything where we don't have a list of people to notify, just a common area that people may subscribe to.
 *
 * The recipient is Global which means it does not change its delivery channel depending on Business Unit or Machine
 * Group. It is always delivered to the same place regardless of the Notification type.
 *
 * @package App\Notifications
 */
class GlobalNotifiable implements HasLocalePreference
{
    use Notifiable;

    public function preferredLocale()
    {
        return 'en'; // TODO: globally configurable system locale for broadcast messages.
    }

    public function routeNotificationForSlack(Notification $notification)
    {
        return config('services.slack.webhook_url');
    }

    public function routeNotificationForMicrosoftTeams(Notification $notification)
    {
        return config('services.teams.webhook_url');
    }
}
