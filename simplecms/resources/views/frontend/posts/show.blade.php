@extends('frontend.layouts.app')

@section('title', $post->title . ' - ' . ($settings['site_name'] ?? 'SimpleCMS'))
@section('meta_description', $post->meta_description ?? $post->excerpt ?? Str::limit(strip_tags($post->content), 160))
@section('meta_keywords', $post->meta_keywords ?? '')

@section('content')
    {{-- Breadcrumb --}}
    <section class="bg-light py-3">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('blog.index') }}">Blog</a></li>
                    @if($post->category)
                        <li class="breadcrumb-item"><a href="{{ route('blog.category', $post->category->slug) }}">{{ $post->category->name }}</a></li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($post->title, 50) }}</li>
                </ol>
            </nav>
        </div>
    </section>

    {{-- Post Content --}}
    <section class="section">
        <div class="container">
            <div class="row g-4">
                {{-- Main Content --}}
                <div class="col-lg-8">
                    <article class="post-single">
                        {{-- Post Header --}}
                        <header class="mb-4">
                            {{-- Category Badge --}}
                            @if($post->category)
                                <a href="{{ route('blog.category', $post->category->slug) }}" class="badge bg-primary text-decoration-none mb-3">
                                    {{ $post->category->name }}
                                </a>
                            @endif

                            {{-- Title --}}
                            <h1 class="display-5 fw-bold mb-3">{{ $post->title }}</h1>

                            {{-- Meta Info --}}
                            <div class="d-flex align-items-center flex-wrap gap-3 text-muted mb-4">
                                @if($post->author)
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $post->author->getAvatarUrl() }}"
                                             alt="{{ $post->author->name }}"
                                             class="rounded-circle me-2"
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                        <div>
                                            <div class="fw-semibold text-dark">{{ $post->author->name }}</div>
                                            <small>{{ $post->published_at->format('M d, Y') }}</small>
                                        </div>
                                    </div>
                                @else
                                    <span>
                                        <i class="far fa-calendar me-1"></i>{{ $post->published_at->format('M d, Y') }}
                                    </span>
                                @endif

                                @if($post->views_count)
                                    <span>
                                        <i class="far fa-eye me-1"></i>{{ $post->views_count }} views
                                    </span>
                                @endif

                                <span>
                                    <i class="far fa-clock me-1"></i>{{ $post->reading_time ?? ceil(str_word_count(strip_tags($post->content)) / 200) }} min read
                                </span>
                            </div>
                        </header>

                        {{-- Featured Image --}}
                        @if($post->featured_image)
                            <div class="post-featured-image mb-4">
                                <img src="{{ asset('storage/' . $post->featured_image) }}" class="img-fluid rounded shadow-sm w-100" alt="{{ $post->title }}" style="max-height: 500px; object-fit: cover;">
                            </div>
                        @endif

                        {{-- Post Content --}}
                        <div class="post-content">
                            {!! $post->content !!}
                        </div>

                        {{-- Tags --}}
                        @if($post->tags && $post->tags->count() > 0)
                            <div class="post-tags mt-4 pt-4 border-top">
                                <h6 class="text-muted mb-3"><i class="fas fa-tags me-2"></i>Tags:</h6>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($post->tags as $tag)
                                        <a href="{{ route('blog.tag', $tag->slug) }}" class="badge bg-light text-dark text-decoration-none py-2 px-3" style="font-size: 14px;">
                                            <i class="fas fa-tag me-1"></i>{{ $tag->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Post Footer --}}
                        <footer class="post-footer mt-5 pt-4 border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                {{-- Share Buttons --}}
                                <div>
                                    <span class="text-muted me-2">Share:</span>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="btn btn-sm btn-outline-primary me-1" title="Share on Facebook">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}" target="_blank" class="btn btn-sm btn-outline-info me-1" title="Share on Twitter">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&title={{ urlencode($post->title) }}" target="_blank" class="btn btn-sm btn-outline-primary me-1" title="Share on LinkedIn">
                                        <i class="fab fa-linkedin-in"></i>
                                    </a>
                                    <a href="whatsapp://send?text={{ urlencode($post->title . ' - ' . url()->current()) }}" target="_blank" class="btn btn-sm btn-outline-success" title="Share on WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                </div>

                                {{-- Last Updated --}}
                                @if($post->updated_at->gt($post->published_at))
                                    <small class="text-muted">
                                        <i class="far fa-edit me-1"></i>Last updated: {{ $post->updated_at->format('M d, Y') }}
                                    </small>
                                @endif
                            </div>
                        </footer>

                        {{-- Author Bio --}}
                        @if($post->author)
                            <div class="author-bio card border-0 shadow-sm mt-4 p-4">
                                <div class="d-flex align-items-start">
                                    <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 80px; height: 80px; font-size: 2rem;">
                                        {{ substr($post->author->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h5 class="mb-2">{{ $post->author->name }}</h5>
                                        <p class="text-muted mb-0">
                                            {{ $post->author->bio ?? 'Content creator and writer' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </article>

                    {{-- Related Posts --}}
                    @if(isset($relatedPosts) && $relatedPosts->count() > 0)
                        <section class="related-posts mt-5">
                            <h3 class="mb-4">Related Posts</h3>
                            <div class="row g-4">
                                @foreach($relatedPosts as $relatedPost)
                                    <div class="col-md-4">
                                        <article class="card border-0 shadow-sm h-100 hover-lift">
                                            @if($relatedPost->featured_image)
                                                <a href="{{ route('blog.show', $relatedPost->slug) }}">
                                                    <img src="{{ asset('storage/' . $relatedPost->featured_image) }}" class="card-img-top" alt="{{ $relatedPost->title }}" style="height: 180px; object-fit: cover;">
                                                </a>
                                            @endif
                                            <div class="card-body">
                                                <h5 class="card-title">
                                                    <a href="{{ route('blog.show', $relatedPost->slug) }}" class="text-decoration-none text-dark">
                                                        {{ Str::limit($relatedPost->title, 60) }}
                                                    </a>
                                                </h5>
                                                <p class="card-text text-muted small">
                                                    <i class="far fa-calendar me-1"></i>{{ $relatedPost->published_at->format('M d, Y') }}
                                                </p>
                                            </div>
                                        </article>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endif
                </div>

                {{-- Sidebar --}}
                <div class="col-lg-4">
                    {{-- Recent Posts Widget --}}
                    @php
                        $recentPosts = \App\Models\Post::published()
                            ->where('id', '!=', $post->id)
                            ->latest('published_at')
                            ->take(5)
                            ->get();
                    @endphp

                    @if($recentPosts->count() > 0)
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <h5 class="card-title fw-bold mb-3">Recent Posts</h5>
                                <div class="list-group list-group-flush">
                                    @foreach($recentPosts as $recentPost)
                                        <a href="{{ route('blog.show', $recentPost->slug) }}" class="list-group-item list-group-item-action border-0 px-0">
                                            <div class="d-flex">
                                                @if($recentPost->featured_image)
                                                    <img src="{{ asset('storage/' . $recentPost->featured_image) }}" class="me-3 rounded" alt="{{ $recentPost->title }}" style="width: 60px; height: 60px; object-fit: cover;">
                                                @endif
                                                <div>
                                                    <h6 class="mb-1 small">{{ Str::limit($recentPost->title, 50) }}</h6>
                                                    <small class="text-muted">
                                                        <i class="far fa-calendar me-1"></i>{{ $recentPost->published_at->format('M d, Y') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Categories Widget --}}
                    @php
                        $categories = \App\Models\Category::withCount(['posts' => function ($query) {
                            $query->published();
                        }])
                        ->having('posts_count', '>', 0)
                        ->get();
                    @endphp

                    @if($categories->count() > 0)
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <h5 class="card-title fw-bold mb-3">Categories</h5>
                                <ul class="list-unstyled mb-0">
                                    @foreach($categories as $category)
                                        <li class="mb-2">
                                            <a href="{{ route('blog.category', $category->slug) }}" class="text-decoration-none d-flex justify-content-between align-items-center {{ $post->category_id == $category->id ? 'fw-bold text-primary' : '' }}">
                                                <span>
                                                    <i class="fas fa-folder me-2 text-primary"></i>{{ $category->name }}
                                                </span>
                                                <span class="badge bg-light text-dark">{{ $category->posts_count ?? 0 }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .post-content {
        font-size: 1.1rem;
        line-height: 1.8;
    }

    .post-content h1, .post-content h2, .post-content h3,
    .post-content h4, .post-content h5, .post-content h6 {
        margin-top: 2rem;
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .post-content p {
        margin-bottom: 1.5rem;
    }

    .post-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 1.5rem 0;
    }

    .post-content ul, .post-content ol {
        margin-bottom: 1.5rem;
        padding-left: 2rem;
    }

    .post-content li {
        margin-bottom: 0.5rem;
    }

    .post-content blockquote {
        border-left: 4px solid var(--primary-color);
        padding-left: 1.5rem;
        margin: 2rem 0;
        font-style: italic;
        color: #666;
    }

    .post-content code {
        background-color: #f4f4f4;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.9em;
    }

    .post-content pre {
        background-color: #f4f4f4;
        padding: 1rem;
        border-radius: 8px;
        overflow-x: auto;
        margin: 1.5rem 0;
    }

    .post-content table {
        width: 100%;
        margin: 1.5rem 0;
        border-collapse: collapse;
    }

    .post-content table th,
    .post-content table td {
        padding: 0.75rem;
        border: 1px solid #ddd;
    }

    .post-content table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }

    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.15) !important;
    }

    .list-group-item {
        transition: background-color 0.2s ease;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
    }

    .author-bio {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
</style>
@endpush
