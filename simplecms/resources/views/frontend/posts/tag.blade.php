@extends('frontend.layouts.app')

@section('title', $tag->name . ' - Blog - ' . ($settings['site_name'] ?? 'SimpleCMS'))
@section('meta_description', $tag->description ?? 'Browse posts tagged with ' . $tag->name)

@section('content')
    {{-- Breadcrumb --}}
    <section class="bg-light py-3">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('blog.index') }}">Blog</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tag: {{ $tag->name }}</li>
                </ol>
            </nav>
        </div>
    </section>

    {{-- Page Header --}}
    <section class="bg-primary text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <div class="mb-3">
                        <span class="badge bg-white text-primary fs-5 px-3 py-2">
                            <i class="fas fa-tag me-2"></i>{{ $tag->name }}
                        </span>
                    </div>
                    @if($tag->description)
                        <p class="lead mb-0">{{ $tag->description }}</p>
                    @endif
                    <p class="mt-2 mb-0">
                        <small>{{ $posts->total() }} {{ Str::plural('post', $posts->total()) }} with this tag</small>
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Blog Content --}}
    <section class="section">
        <div class="container">
            <div class="row g-4">
                {{-- Main Content --}}
                <div class="col-lg-8">
                    @if($posts->count() > 0)
                        <div class="row g-4">
                            @foreach($posts as $post)
                                <div class="col-12">
                                    <article class="card border-0 shadow-sm h-100 hover-lift">
                                        <div class="row g-0">
                                            {{-- Featured Image --}}
                                            @if($post->featured_image)
                                                <div class="col-md-4">
                                                    <a href="{{ route('blog.show', $post->slug) }}">
                                                        <img src="{{ asset('storage/' . $post->featured_image) }}" class="img-fluid h-100 w-100" alt="{{ $post->title }}" style="object-fit: cover; min-height: 250px;">
                                                    </a>
                                                </div>
                                            @endif

                                            {{-- Post Content --}}
                                            <div class="{{ $post->featured_image ? 'col-md-8' : 'col-12' }}">
                                                <div class="card-body p-4">
                                                    {{-- Meta Info --}}
                                                    <div class="d-flex align-items-center mb-3 flex-wrap gap-2">
                                                        @if($post->category)
                                                            <span class="badge bg-primary">
                                                                {{ $post->category->name }}
                                                            </span>
                                                        @endif
                                                        <span class="text-muted small">
                                                            <i class="far fa-calendar me-1"></i>{{ $post->published_at->format('M d, Y') }}
                                                        </span>
                                                        @if($post->views_count)
                                                            <span class="text-muted small">
                                                                <i class="far fa-eye me-1"></i>{{ $post->views_count }} views
                                                            </span>
                                                        @endif
                                                    </div>

                                                    {{-- Title --}}
                                                    <h3 class="card-title mb-3">
                                                        <a href="{{ route('blog.show', $post->slug) }}" class="text-decoration-none text-dark">
                                                            {{ $post->title }}
                                                        </a>
                                                    </h3>

                                                    {{-- Excerpt --}}
                                                    @if($post->excerpt)
                                                        <p class="card-text text-muted mb-3">{{ $post->excerpt }}</p>
                                                    @else
                                                        <p class="card-text text-muted mb-3">{{ Str::limit(strip_tags($post->content), 150) }}</p>
                                                    @endif

                                                    {{-- Tags --}}
                                                    @if($post->tags && $post->tags->count() > 0)
                                                        <div class="mb-3">
                                                            @foreach($post->tags as $postTag)
                                                                <a href="{{ route('blog.tag', $postTag->slug) }}" class="badge bg-light text-dark text-decoration-none me-1">
                                                                    <i class="fas fa-tag me-1"></i>{{ $postTag->name }}
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    @endif

                                                    {{-- Author & Read More --}}
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        @if($post->author)
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                                    {{ substr($post->author->name, 0, 1) }}
                                                                </div>
                                                                <small class="text-muted">{{ $post->author->name }}</small>
                                                            </div>
                                                        @endif
                                                        <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-outline-primary btn-sm">
                                                            Read More <i class="fas fa-arrow-right ms-1"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-5">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>No posts found with this tag.
                        </div>
                        <div class="text-center">
                            <a href="{{ route('blog.index') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left me-2"></i>Browse All Posts
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Sidebar --}}
                <div class="col-lg-4">
                    {{-- Search Widget --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-3">Search</h5>
                            <form action="{{ route('search') }}" method="GET">
                                <div class="input-group">
                                    <input type="search" name="q" class="form-control" placeholder="Search posts..." value="{{ request('q') }}">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Popular Tags --}}
                    @if(isset($popularTags) && $popularTags->count() > 0)
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <h5 class="card-title fw-bold mb-3">Popular Tags</h5>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($popularTags as $popTag)
                                        <a href="{{ route('blog.tag', $popTag->slug) }}" class="badge {{ $tag->id == $popTag->id ? 'bg-primary' : 'bg-light text-dark' }} text-decoration-none py-2 px-3" style="font-size: 14px;">
                                            <i class="fas fa-tag me-1"></i>{{ $popTag->name }}
                                            <span class="badge {{ $tag->id == $popTag->id ? 'bg-white text-primary' : 'bg-secondary' }} ms-1">{{ $popTag->posts_count ?? 0 }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Categories Widget --}}
                    @if(isset($categories) && $categories->count() > 0)
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <h5 class="card-title fw-bold mb-3">Categories</h5>
                                <ul class="list-unstyled mb-0">
                                    @foreach($categories as $cat)
                                        <li class="mb-2">
                                            <a href="{{ route('blog.category', $cat->slug) }}" class="text-decoration-none d-flex justify-content-between align-items-center">
                                                <span>
                                                    <i class="fas fa-folder me-2 text-muted"></i>{{ $cat->name }}
                                                </span>
                                                <span class="badge bg-light text-dark">
                                                    {{ $cat->posts_count ?? 0 }}
                                                </span>
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

    .bg-primary {
        background-color: var(--primary-color) !important;
    }

    .text-primary {
        color: var(--primary-color) !important;
    }

    .badge:hover {
        opacity: 0.8;
    }
</style>
@endpush
