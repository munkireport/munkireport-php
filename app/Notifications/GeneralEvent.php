<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;

/**
 * GeneralEvent
 *
 * This notification represents a backwards-compatible way of converting events that were traditionally stored into
 * the `event` table, by the function site_helpers.php:store_event() *OR* KissMvc Model->store_event().
 *
 * The general event takes the same method signature expected by store_event(), which is then converted into a
 * different payload per channel.
 *
 * @package App\Notifications
 */
class GeneralEvent extends Notification
{
    use Queueable;

    protected $serial;
    protected $module;
    protected $type;
    protected $msg;
    protected $data;

    /**
     * Create a new notification instance based on the MunkiReport store_event() function.
     *
     * @param string $serial Serial number of the machine reporting in.
     * @param string $module The module which raised the event (in some cases the database table - in KISS MVC).
     * @param string $type The category or severity of the event.
     * @param string $msg A plaintext message explaining the event, or a magic string indicating a value in a locale to display
     *                    a localized version of that event message.
     * @param string $data A JSON serialized dictionary of additional data to store with the event that may provide more
     *                     context.
     */
    public function __construct(string $serial, string $module = '', string $type = '',
        string $msg = 'no_message', string $data = '')
    {
        $this->serial = $serial;
        $this->module = $module;
        $this->type = $type;
        $this->msg = $msg;
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['database', 'slack'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        return [
            'serial' => $this->serial,
            'module' => $this->module,
            'type' => $this->type,
            'msg' => $this->msg,
            'data' => $this->data,
        ];
    }

    /**
     * Get the slack representation of the notification.
     *
     * @param mixed $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable): SlackMessage
    {
        return (new SlackMessage)
            ->from(config('_munkireport.notifications.slack.from', 'MunkiReport'), ':ghost:')
            ->to(config('_munkireport.notifications.slack.to', ''))
            ->content(__('messages.event') . ": {$this->msg}");
    }
}
