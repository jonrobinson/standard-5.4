<?php

namespace App\Listeners;

use App\Events\GroupApproved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\SendNewGroupMemberRegisterEmail;

class DispatchRegisterEmail implements ShouldQueue
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
     * @param  SomeEvent  $event
     * @return void
     */
    public function handle(GroupApproved $event)
    {
        $group = $event->group;
        foreach ($group->members as $user) {
            dispatch(new SendNewGroupMemberRegisterEmail($user, $group));
        }
    }
}
