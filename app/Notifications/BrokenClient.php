<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;

class BrokenClient extends Notification
{
    use Queueable;

    protected $serial;
    protected $module;
    protected $type;
    protected $msg;
    protected $name;

    /**
     * Create a new notification when a munki client breaks (via report_broken_client)
     *
     * @param string $serial Serial number of the machine reporting in.
     * @param string $module The module which raised the event (in some cases the database table - in KISS MVC).
     * @param string $type The category or severity of the event.
     * @param string $msg A plaintext message explaining the event, or a magic string indicating a value in a locale to display
     *                    a localized version of that event message.
     * @param string $name
     */
    public function __construct(string $msg, string $module, string $type, string $serial, string $name)
    {
        $this->serial = $serial;
        $this->module = $module;
        $this->type = $type;
        $this->msg = $msg;
        $this->name = $name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via(Notifiable $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray(Notifiable $notifiable): array
    {
        return [
            'serial' => $this->serial,
            'module' => $this->module,
            'type' => $this->type,
            'msg' => $this->msg,
            'name' => $this->name,
        ];
    }
}
