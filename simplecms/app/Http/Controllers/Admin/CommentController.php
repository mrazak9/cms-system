<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Constructor - Apply middleware for permissions
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:comments.view')->only(['index', 'show']);
        $this->middleware('permission:comments.moderate')->only(['approve', 'markAsSpam']);
        $this->middleware('permission:comments.delete')->only('destroy');
    }

    /**
     * Display a listing of comments
     */
    public function index(Request $request)
    {
        $query = Comment::with(['post', 'user'])->latest();

        // Filter by status
        if ($request->has('status') && in_array($request->status, ['pending', 'approved', 'spam', 'trash'])) {
            $query->where('status', $request->status);
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('content', 'like', "%{$search}%")
                  ->orWhere('author_name', 'like', "%{$search}%")
                  ->orWhere('author_email', 'like', "%{$search}%");
            });
        }

        $comments = $query->paginate(20);

        // Get statistics
        $stats = [
            'total' => Comment::count(),
            'pending' => Comment::pending()->count(),
            'approved' => Comment::approved()->count(),
            'spam' => Comment::spam()->count(),
        ];

        return view('admin.comments.index', compact('comments', 'stats'));
    }

    /**
     * Approve a comment
     */
    public function approve($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->approve();

        return redirect()->back()->with('success', 'Comment approved successfully.');
    }

    /**
     * Mark comment as spam
     */
    public function markAsSpam($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->markAsSpam();

        return redirect()->back()->with('success', 'Comment marked as spam.');
    }

    /**
     * Delete a comment
     */
    public function destroy($id)
    {
        try {
            $comment = Comment::findOrFail($id);
            $comment->delete();

            return redirect()->route('admin.comments.index')
                ->with('success', 'Comment deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting comment: ' . $e->getMessage());
        }
    }
}
