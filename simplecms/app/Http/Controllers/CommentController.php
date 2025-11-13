<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Store a new comment
     *
     * @param \Illuminate\Http\Request $request
     * @param int $postId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $postId)
    {
        // Find the post
        $post = Post::findOrFail($postId);

        // Check if comments are enabled
        $commentsEnabled = Setting::get('comments_enabled', true);
        if (!$commentsEnabled) {
            return redirect()->back()->with('error', 'Comments are currently disabled.');
        }

        // Validation rules
        $rules = [
            'content' => 'required|string|min:3|max:5000',
            'parent_id' => 'nullable|exists:comments,id',
        ];

        // If user is not logged in, require name and email
        if (!auth()->check()) {
            $rules['author_name'] = 'required|string|max:255';
            $rules['author_email'] = 'required|email|max:255';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Determine comment status
            $requiresModeration = Setting::get('comments_require_moderation', true);
            $status = $requiresModeration ? Comment::STATUS_PENDING : Comment::STATUS_APPROVED;

            // If user is logged in and has comment approval bypass permission, auto-approve
            if (auth()->check() && auth()->user()->can('comments.bypass-moderation')) {
                $status = Comment::STATUS_APPROVED;
            }

            // Create the comment
            $comment = Comment::create([
                'post_id' => $post->id,
                'user_id' => auth()->id(),
                'parent_id' => $request->parent_id,
                'author_name' => auth()->check() ? auth()->user()->name : $request->author_name,
                'author_email' => auth()->check() ? auth()->user()->email : $request->author_email,
                'content' => $request->content,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => $status,
            ]);

            $message = $status === Comment::STATUS_APPROVED
                ? 'Your comment has been posted successfully.'
                : 'Your comment has been submitted and is awaiting moderation.';

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while submitting your comment. Please try again.')
                ->withInput();
        }
    }
}
