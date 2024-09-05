<?php

namespace Vncore\Core\Admin\Listeners;

use Vncore\Core\Admin\Events\EventAdminCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Events\Dispatcher;

class ListenAdminCreated
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
    public function handle(EventAdminCreated $event)
    {
        $user = $event->user;
        if (function_exists('partner_listen_admin_add')) {
            partner_listen_admin_add($user);
        }
        vncore_notice_add(type: 'Admin', typeId: $user->id, content:'admin_notice.vncore_new_admin_add');
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            EventAdminCreated::class,
            [ListenAdminCreated::class, 'handle']
        );
    }

}
