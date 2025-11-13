<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use Illuminate\Http\Request;

class ContactSubmissionController extends Controller
{
    /**
     * Constructor - Apply middleware for permissions
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:contact.view')->only(['index', 'show']);
        $this->middleware('permission:contact.delete')->only('destroy');
    }

    /**
     * Display a listing of contact submissions
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = ContactSubmission::query()->latest();

        // Filter by status
        if ($request->has('status') && in_array($request->status, ['read', 'unread'])) {
            if ($request->status === 'unread') {
                $query->unread();
            } else {
                $query->read();
            }
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $submissions = $query->paginate(20);

        // Get statistics
        $stats = [
            'total' => ContactSubmission::count(),
            'unread' => ContactSubmission::unread()->count(),
            'read' => ContactSubmission::read()->count(),
        ];

        return view('admin.contact-submissions.index', compact('submissions', 'stats'));
    }

    /**
     * Display a single contact submission
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $submission = ContactSubmission::findOrFail($id);

        // Mark as read when viewing
        if ($submission->isUnread()) {
            $submission->markAsRead();
        }

        return view('admin.contact-submissions.show', compact('submission'));
    }

    /**
     * Mark submission as read
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsRead($id)
    {
        $submission = ContactSubmission::findOrFail($id);
        $submission->markAsRead();

        return redirect()->back()->with('success', 'Submission marked as read.');
    }

    /**
     * Mark submission as unread
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsUnread($id)
    {
        $submission = ContactSubmission::findOrFail($id);
        $submission->markAsUnread();

        return redirect()->back()->with('success', 'Submission marked as unread.');
    }

    /**
     * Delete a contact submission
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $submission = ContactSubmission::findOrFail($id);
            $submission->delete();

            return redirect()->route('admin.contact-submissions.index')
                ->with('success', 'Contact submission deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting submission: ' . $e->getMessage());
        }
    }
}
