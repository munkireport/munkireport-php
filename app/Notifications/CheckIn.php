<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;

/**
 * The check-in notification is fired every single time a client checks in.
 * *WARNING* use sparingly because you will be flooded if you have lots of clients.
 *
 * @package App\Notifications
 */
class CheckIn extends Notification
{
    use Queueable;

    protected $serialNumber;

    /**
     * Create a new check-in notification instance.
     *
     * @param string $serialNumber
     */
    public function __construct(string $serialNumber)
    {
        $this->serialNumber = $serialNumber;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the slack representation of the notification.
     *
     * @param Notifiable $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable): SlackMessage
    {
        return (new SlackMessage)
            ->from(config('_munkireport.notifications.slack.from', 'MunkiReport'), ':ghost:')
            ->to(config('_munkireport.notifications.slack.to', ''))
            ->content(__('messages.listing.checkin') . ' ' . $this->serialNumber);
    }
}
