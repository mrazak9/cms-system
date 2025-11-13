<?php

namespace App\Jobs;

use App\Models\Post;
use App\Models\Page;
use App\Models\ActivityLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PublishScheduledContent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $now = Carbon::now();
        $publishedCount = 0;

        // Publish scheduled posts
        $scheduledPosts = Post::where('workflow_status', 'scheduled')
            ->where('scheduled_publish_at', '<=', $now)
            ->whereNotNull('scheduled_publish_at')
            ->get();

        foreach ($scheduledPosts as $post) {
            $post->status = 'published';
            $post->workflow_status = 'published';
            $post->published_at = $now;
            $post->save();

            ActivityLog::log(
                ActivityLog::TYPE_CREATE,
                "Post '{$post->title}' automatically published from schedule",
                $post
            );

            Log::info("Published scheduled post: {$post->title} (ID: {$post->id})");
            $publishedCount++;
        }

        // Publish scheduled pages
        $scheduledPages = Page::where('workflow_status', 'scheduled')
            ->where('scheduled_publish_at', '<=', $now)
            ->whereNotNull('scheduled_publish_at')
            ->get();

        foreach ($scheduledPages as $page) {
            $page->status = 'published';
            $page->workflow_status = 'published';
            $page->published_at = $now;
            $page->save();

            ActivityLog::log(
                ActivityLog::TYPE_CREATE,
                "Page '{$page->title}' automatically published from schedule",
                $page
            );

            Log::info("Published scheduled page: {$page->title} (ID: {$page->id})");
            $publishedCount++;
        }

        if ($publishedCount > 0) {
            Log::info("Published {$publishedCount} scheduled content items");
        }
    }
}
