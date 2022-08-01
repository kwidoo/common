<?php

namespace Velia\Common\Listeners;

use Velia\Common\Events\Logout;
use Velia\Common\Models\User;

class LogoutListener
{
    public function handle(Logout $event)
    {
        $user = User::find($event->user_id);
        $user->update([
            'access_token' => null,
            'refresh_token' => null
        ]);
    }
}
