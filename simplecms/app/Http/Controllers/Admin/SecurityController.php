<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FailedLoginAttempt;
use App\Models\User;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    /**
     * Display security dashboard
     */
    public function index()
    {
        $this->authorize('security.view');

        // Get statistics
        $stats = FailedLoginAttempt::getStatistics();

        // Get recent failed attempts
        $recentAttempts = FailedLoginAttempt::with([])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Get locked accounts
        $lockedAccounts = User::whereNotNull('locked_until')
            ->where('locked_until', '>', now())
            ->orderBy('locked_until', 'desc')
            ->get();

        return view('admin.security.index', compact('stats', 'recentAttempts', 'lockedAccounts'));
    }

    /**
     * Show failed login attempts
     */
    public function failedLogins(Request $request)
    {
        $this->authorize('security.view');

        $query = FailedLoginAttempt::query();

        // Filter by email
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        // Filter by IP
        if ($request->filled('ip')) {
            $query->where('ip_address', $request->ip);
        }

        // Filter by date
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $attempts = $query->orderBy('created_at', 'desc')->paginate(50);

        return view('admin.security.failed-logins', compact('attempts'));
    }

    /**
     * Show locked accounts
     */
    public function lockedAccounts()
    {
        $this->authorize('security.view');

        $lockedAccounts = User::whereNotNull('locked_until')
            ->where('locked_until', '>', now())
            ->orderBy('locked_until', 'desc')
            ->paginate(20);

        $recentlyUnlocked = User::whereNotNull('locked_until')
            ->where('locked_until', '<=', now())
            ->orderBy('locked_until', 'desc')
            ->limit(10)
            ->get();

        return view('admin.security.locked-accounts', compact('lockedAccounts', 'recentlyUnlocked'));
    }

    /**
     * Unlock a user account
     */
    public function unlockAccount(User $user)
    {
        $this->authorize('security.manage');

        $user->unlockAccount();

        return redirect()
            ->back()
            ->with('success', "Account {$user->email} has been unlocked successfully.");
    }

    /**
     * Lock a user account manually
     */
    public function lockAccount(Request $request, User $user)
    {
        $this->authorize('security.manage');

        $request->validate([
            'duration' => 'required|integer|min:5|max:1440', // 5 minutes to 24 hours
        ]);

        $user->lockAccount($request->duration);

        return redirect()
            ->back()
            ->with('success', "Account {$user->email} has been locked for {$request->duration} minutes.");
    }

    /**
     * Clear old failed login attempts
     */
    public function clearOldAttempts()
    {
        $this->authorize('security.manage');

        $deleted = FailedLoginAttempt::clearOldAttempts(30);

        return redirect()
            ->back()
            ->with('success', "Cleared {$deleted} old failed login attempts.");
    }
}
