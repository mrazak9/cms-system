<?php

namespace App\Http\Controllers;

use App\Models\ContactSubmission;
use App\Models\Setting;
use App\Mail\ContactSubmissionNotification;
use App\Mail\ContactSubmissionAutoReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Display the contact form
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('frontend.contact');
    }

    /**
     * Store a contact form submission
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Create the contact submission
            $submission = ContactSubmission::create([
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject,
                'message' => $request->message,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => ContactSubmission::STATUS_UNREAD,
            ]);

            // Send email notifications if enabled
            $this->sendEmailNotifications($submission);

            // Redirect back with success message
            return redirect()->back()->with('success', 'Thank you for contacting us! We will get back to you soon.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while submitting your message. Please try again.')
                ->withInput();
        }
    }

    /**
     * Send email notifications for new contact submission
     *
     * @param \App\Models\ContactSubmission $submission
     * @return void
     */
    protected function sendEmailNotifications(ContactSubmission $submission)
    {
        try {
            // Check if email notifications are enabled
            $notificationsEnabled = Setting::get('contact_notifications_enabled', true);
            $autoReplyEnabled = Setting::get('contact_auto_reply_enabled', true);

            // Send notification to admin if enabled
            if ($notificationsEnabled) {
                $adminEmail = Setting::get('contact_notification_email')
                    ?? Setting::get('admin_email')
                    ?? config('mail.from.address');

                if ($adminEmail) {
                    Mail::to($adminEmail)
                        ->send(new ContactSubmissionNotification($submission));
                }
            }

            // Send auto-reply to sender if enabled
            if ($autoReplyEnabled) {
                Mail::to($submission->email)
                    ->send(new ContactSubmissionAutoReply($submission));
            }

        } catch (\Exception $e) {
            // Log the error but don't fail the submission
            \Log::error('Failed to send contact form email: ' . $e->getMessage());
        }
    }
}
