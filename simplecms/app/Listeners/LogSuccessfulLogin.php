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
        ActivityLog::log(
            ActivityLog::TYPE_LOGIN,
            'User logged in',
            null,
            ['email' => $event->user->email]
        );
    }
}
