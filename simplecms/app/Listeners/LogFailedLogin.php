<?php

namespace App\Listeners;

use App\Models\ActivityLog;
use App\Models\FailedLoginAttempt;
use App\Models\User;
use Illuminate\Auth\Events\Failed;

class LogFailedLogin
{
    /**
     * Handle the event.
     */
    public function handle(Failed $event): void
    {
        $email = $event->credentials['email'] ?? null;

        // Log the failed attempt
        if ($email) {
            FailedLoginAttempt::logAttempt($email, 'invalid_credentials');

            // Find user and increment failed attempts
            $user = User::where('email', $email)->first();
            if ($user) {
                $user->incrementFailedLoginAttempts();

                // Log activity
                ActivityLog::log(
                    ActivityLog::TYPE_LOGIN,
                    "Failed login attempt from IP: " . request()->ip(),
                    $user
                );
            }
        }

        // Also log by IP if no email (could be bot)
        FailedLoginAttempt::create([
            'email' => $email,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'reason' => 'invalid_credentials',
        ]);
    }
}
