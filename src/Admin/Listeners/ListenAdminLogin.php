<?php

namespace Vncore\Core\Admin\Listeners;

use Vncore\Core\Admin\Events\EventAdminLogin;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Events\Dispatcher;

class ListenAdminLogin
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
     * @param  Login  $event
     * @return void
     */
    public function handle(EventAdminLogin $event)
    {
        $user = $event->user;
        if (function_exists('partner_listen_admin_login')) {
            partner_listen_admin_login($user);
        }
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            EventAdminLogin::class,
            [ListenAdminLogin::class, 'handle']
        );
    }

}
