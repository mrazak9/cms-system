<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Page;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EditorialCalendarController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('posts.view');

        // Get the current month and year from request or use current
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);

        $date = Carbon::create($year, $month, 1);
        $startDate = $date->copy()->startOfMonth();
        $endDate = $date->copy()->endOfMonth();

        // Get all scheduled and published posts for the month
        $posts = Post::whereBetween('scheduled_publish_at', [$startDate, $endDate])
            ->orWhereBetween('published_at', [$startDate, $endDate])
            ->with('author', 'category')
            ->orderBy('scheduled_publish_at')
            ->orderBy('published_at')
            ->get();

        // Get all scheduled and published pages for the month
        $pages = Page::whereBetween('scheduled_publish_at', [$startDate, $endDate])
            ->orWhereBetween('published_at', [$startDate, $endDate])
            ->with('creator')
            ->orderBy('scheduled_publish_at')
            ->orderBy('published_at')
            ->get();

        // Organize content by date
        $calendar = [];
        foreach ($posts as $post) {
            $contentDate = $post->scheduled_publish_at ?? $post->published_at;
            if ($contentDate) {
                $day = $contentDate->format('Y-m-d');
                if (!isset($calendar[$day])) {
                    $calendar[$day] = ['posts' => [], 'pages' => []];
                }
                $calendar[$day]['posts'][] = $post;
            }
        }

        foreach ($pages as $page) {
            $contentDate = $page->scheduled_publish_at ?? $page->published_at;
            if ($contentDate) {
                $day = $contentDate->format('Y-m-d');
                if (!isset($calendar[$day])) {
                    $calendar[$day] = ['posts' => [], 'pages' => []];
                }
                $calendar[$day]['pages'][] = $page;
            }
        }

        // Get workflow statistics
        $stats = [
            'draft' => Post::draft()->count() + Page::draft()->count(),
            'pending_review' => Post::pendingReview()->count() + Page::pendingReview()->count(),
            'scheduled' => Post::scheduled()->count() + Page::scheduled()->count(),
            'published_this_month' => Post::whereBetween('published_at', [$startDate, $endDate])
                ->where('workflow_status', 'published')->count() +
                Page::whereBetween('published_at', [$startDate, $endDate])
                ->where('workflow_status', 'published')->count(),
        ];

        return view('admin.calendar.index', compact('calendar', 'date', 'stats'));
    }

    public function upcoming(Request $request)
    {
        $this->authorize('posts.view');

        // Get all upcoming scheduled content
        $scheduledPosts = Post::scheduled()
            ->where('scheduled_publish_at', '>', Carbon::now())
            ->with('author', 'category')
            ->orderBy('scheduled_publish_at')
            ->paginate(20);

        $scheduledPages = Page::scheduled()
            ->where('scheduled_publish_at', '>', Carbon::now())
            ->with('creator')
            ->orderBy('scheduled_publish_at')
            ->paginate(20);

        return view('admin.calendar.upcoming', compact('scheduledPosts', 'scheduledPages'));
    }
}
