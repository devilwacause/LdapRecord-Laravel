<?php

namespace LdapRecord\Laravel\Listeners;

use Illuminate\Support\Facades\Log;
use LdapRecord\Laravel\Events\Authenticated;

class LogAuthenticated
{
    /**
     * Handle the event.
     *
     * @param Authenticated $event
     *
     * @return void
     */
    public function handle(Authenticated $event)
    {
        Log::info("User '{$event->user->getCommonName()}' has successfully passed LDAP authentication.");
    }
}
