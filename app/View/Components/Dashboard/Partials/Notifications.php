<?php

namespace App\View\Components\Dashboard\Partials;

use Illuminate\Contracts\View\View;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\View\Component;

class Notifications extends Component
{
    /**
     * @var DatabaseNotification[]|DatabaseNotificationCollection
     */
    private array|DatabaseNotificationCollection $unread_notifications;

    public function __construct()
    {
        $this->unread_notifications = admin()->notifications()->unread()->get();
    }

    public function render(): View
    {
        return view('components.dashboard.partials.notifications',[
            'unread_notifications' => $this->unread_notifications
        ]);
    }
}
