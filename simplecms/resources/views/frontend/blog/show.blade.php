@extends('frontend.layouts.app')

@section('title', $post->title . ' - ' . ($settings['site_name'] ?? 'SimpleCMS'))
@section('meta_description', $post->meta_description ?? $post->excerpt)
@section('meta_keywords', $post->meta_keywords ?? '')

@section('og_title', $post->title)
@section('og_description', $post->excerpt)
@section('og_image', $post->featured_image ? asset($post->featured_image) : '')

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
            <div class="row">
                {{-- Main Content --}}
                <div class="col-lg-8 mx-auto">
                    <article class="post-single">
                        {{-- Post Header --}}
                        <header class="mb-4">
                            {{-- Category & Meta --}}
                            <div class="mb-3">
                                @if($post->category)
                                    <a href="{{ route('blog.category', $post->category->slug) }}" class="badge bg-primary text-decoration-none">
                                        {{ $post->category->name }}
                                    </a>
                                @endif
                                <span class="text-muted ms-3 small">
                                    <i class="far fa-calendar me-1"></i>{{ $post->published_at->format('F d, Y') }}
                                </span>
                                <span class="text-muted ms-3 small">
                                    <i class="far fa-eye me-1"></i>{{ $post->views_count ?? 0 }} views
                                </span>
                            </div>

                            {{-- Title --}}
                            <h1 class="display-5 fw-bold mb-4">{{ $post->title }}</h1>

                            {{-- Author Info --}}
                            @if($post->author)
                                <div class="d-flex align-items-center mb-4">
                                    <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; font-size: 1.2rem;">
                                        {{ substr($post->author->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $post->author->name }}</div>
                                        <small class="text-muted">Author</small>
                                    </div>
                                </div>
                            @endif

                            {{-- Featured Image --}}
                            @if($post->featured_image)
                                <div class="post-image mb-4">
                                    <img src="{{ asset($post->featured_image) }}" class="img-fluid rounded shadow" alt="{{ $post->title }}">
                                </div>
                            @endif
                        </header>

                        {{-- Post Content --}}
                        <div class="post-content mb-5">
                            @if($post->excerpt)
                                <p class="lead text-muted">{{ $post->excerpt }}</p>
                                <hr class="my-4">
                            @endif

                            <div class="content">
                                {!! $post->content !!}
                            </div>
                        </div>

                        {{-- Post Footer --}}
                        <footer class="post-footer">
                            {{-- Tags --}}
                            @if($post->meta_keywords)
                                <div class="mb-4">
                                    <strong class="me-2">Tags:</strong>
                                    @foreach(explode(',', $post->meta_keywords) as $tag)
                                        <a href="{{ route('blog.index', ['tag' => trim($tag)]) }}" class="badge bg-light text-dark text-decoration-none me-2">
                                            #{{ trim($tag) }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Share Buttons --}}
                            <div class="share-buttons mb-4">
                                <strong class="me-2">Share:</strong>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="btn btn-sm btn-outline-primary me-2">
                                    <i class="fab fa-facebook-f me-1"></i>Facebook
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}" target="_blank" class="btn btn-sm btn-outline-info me-2">
                                    <i class="fab fa-twitter me-1"></i>Twitter
                                </a>
                                <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&title={{ urlencode($post->title) }}" target="_blank" class="btn btn-sm btn-outline-primary me-2">
                                    <i class="fab fa-linkedin-in me-1"></i>LinkedIn
                                </a>
                                <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . url()->current()) }}" target="_blank" class="btn btn-sm btn-outline-success">
                                    <i class="fab fa-whatsapp me-1"></i>WhatsApp
                                </a>
                            </div>
                        </footer>
                    </article>

                    {{-- Author Bio --}}
                    @if($post->author)
                        <div class="card border-0 shadow-sm mb-5">
                            <div class="card-body p-4">
                                <div class="d-flex">
                                    <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 64px; height: 64px; font-size: 1.5rem;">
                                        {{ substr($post->author->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h5 class="mb-2">{{ $post->author->name }}</h5>
                                        <p class="text-muted mb-0">Author at {{ $settings['site_name'] ?? 'SimpleCMS' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Related Posts --}}
                    @if(isset($relatedPosts) && $relatedPosts->count() > 0)
                        <div class="related-posts mb-5">
                            <h3 class="fw-bold mb-4">Related Articles</h3>
                            <div class="row g-4">
                                @foreach($relatedPosts as $relatedPost)
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm h-100 hover-lift">
                                            @if($relatedPost->featured_image)
                                                <img src="{{ asset($relatedPost->featured_image) }}" class="card-img-top" alt="{{ $relatedPost->title }}" style="height: 180px; object-fit: cover;">
                                            @endif
                                            <div class="card-body">
                                                <h5 class="card-title mb-2">
                                                    <a href="{{ route('blog.show', $relatedPost->slug) }}" class="text-decoration-none text-dark">
                                                        {{ $relatedPost->title }}
                                                    </a>
                                                </h5>
                                                <p class="card-text text-muted small">{{ Str::limit($relatedPost->excerpt, 100) }}</p>
                                                <small class="text-muted">
                                                    <i class="far fa-calendar me-1"></i>{{ $relatedPost->published_at->format('M d, Y') }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Prev/Next Navigation --}}
                    <nav class="post-navigation">
                        <div class="row g-3">
                            @if(isset($prevPost))
                                <div class="col-md-6">
                                    <a href="{{ route('blog.show', $prevPost->slug) }}" class="card border-0 shadow-sm text-decoration-none h-100">
                                        <div class="card-body">
                                            <small class="text-muted d-block mb-2">
                                                <i class="fas fa-arrow-left me-1"></i>Previous Post
                                            </small>
                                            <h6 class="mb-0 text-dark">{{ Str::limit($prevPost->title, 60) }}</h6>
                                        </div>
                                    </a>
                                </div>
                            @endif

                            @if(isset($nextPost))
                                <div class="col-md-6 {{ !isset($prevPost) ? 'ms-auto' : '' }}">
                                    <a href="{{ route('blog.show', $nextPost->slug) }}" class="card border-0 shadow-sm text-decoration-none h-100">
                                        <div class="card-body text-md-end">
                                            <small class="text-muted d-block mb-2">
                                                Next Post<i class="fas fa-arrow-right ms-1"></i>
                                            </small>
                                            <h6 class="mb-0 text-dark">{{ Str::limit($nextPost->title, 60) }}</h6>
                                        </div>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </nav>
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

    .content h1, .content h2, .content h3,
    .content h4, .content h5, .content h6 {
        margin-top: 2rem;
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .content p {
        margin-bottom: 1.5rem;
    }

    .content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 1.5rem 0;
    }

    .content ul, .content ol {
        margin-bottom: 1.5rem;
        padding-left: 2rem;
    }

    .content li {
        margin-bottom: 0.5rem;
    }

    .content blockquote {
        border-left: 4px solid var(--primary-color);
        padding-left: 1.5rem;
        margin: 2rem 0;
        font-style: italic;
        color: #666;
    }

    .content code {
        background-color: #f4f4f4;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.9em;
    }

    .content pre {
        background-color: #f4f4f4;
        padding: 1rem;
        border-radius: 8px;
        overflow-x: auto;
        margin: 1.5rem 0;
    }

    .content table {
        width: 100%;
        margin: 1.5rem 0;
        border-collapse: collapse;
    }

    .content table th,
    .content table td {
        padding: 0.75rem;
        border: 1px solid #ddd;
    }

    .content table th {
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

    .share-buttons .btn:hover {
        transform: translateY(-2px);
    }
</style>
@endpush
