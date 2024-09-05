<?php

namespace Vncore\Core\Admin\Listeners;

use Vncore\Core\Admin\Events\EventAdminDeleting;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Events\Dispatcher;

class ListenAdminDeleting
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
    public function handle(EventAdminDeleting $event)
    {
        $user = $event->user;
        if (function_exists('partner_listen_admin_delete')) {
            partner_listen_admin_delete($user);
        }
        vncore_notice_add(type: 'Admin', typeId: $user->id, content:'admin_notice.vncore_new_admin_delete');
    }


    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            EventAdminDeleting::class,
            [ListenAdminDeleting::class, 'handle']
        );
    }
}
