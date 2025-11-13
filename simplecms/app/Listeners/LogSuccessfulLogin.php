<?php

namespace App\Listeners;

use App\Models\ActivityLog;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogSuccessfulLogin
{
    public function handle(Login $event)
    {
        $user = $event->user;

        // Update last login information
        $user->updateLastLogin();

        // Log the successful login
        ActivityLog::log(
            ActivityLog::TYPE_LOGIN,
            "Successful login from IP: " . request()->ip(),
            $user
        );
    }
}
