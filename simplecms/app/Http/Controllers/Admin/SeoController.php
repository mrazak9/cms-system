<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Page;
use App\Models\Redirect;
use App\Services\SeoAnalyzer;
use Illuminate\Http\Request;

class SeoController extends Controller
{
    protected $seoAnalyzer;

    public function __construct(SeoAnalyzer $seoAnalyzer)
    {
        $this->seoAnalyzer = $seoAnalyzer;
    }

    /**
     * Display SEO dashboard
     */
    public function index()
    {
        $this->authorize('settings.view');

        // Get SEO statistics
        $stats = [
            'total_posts' => Post::count(),
            'posts_with_meta' => Post::whereNotNull('meta_description')->count(),
            'total_pages' => Page::count(),
            'pages_with_meta' => Page::whereNotNull('meta_description')->count(),
            'total_redirects' => Redirect::count(),
            'active_redirects' => Redirect::active()->count(),
        ];

        // Get recent posts for SEO analysis
        $recentPosts = Post::with('author')
            ->latest()
            ->limit(10)
            ->get()
            ->map(function($post) {
                $analysis = $this->seoAnalyzer->analyzeContent(
                    $post->title,
                    $post->content,
                    $post->meta_description,
                    $post->slug
                );
                return [
                    'post' => $post,
                    'seo_score' => $analysis['score'],
                    'seo_grade' => $analysis['grade'],
                ];
            });

        return view('admin.seo.index', compact('stats', 'recentPosts'));
    }

    /**
     * Analyze specific post or page
     */
    public function analyze(Request $request)
    {
        $this->authorize('settings.view');

        $type = $request->input('type'); // 'post' or 'page'
        $id = $request->input('id');

        if ($type === 'post') {
            $item = Post::findOrFail($id);
        } else {
            $item = Page::findOrFail($id);
        }

        $analysis = $this->seoAnalyzer->analyzeContent(
            $item->title,
            $item->content ?? '',
            $item->meta_description,
            $item->slug
        );

        return response()->json($analysis);
    }
}
