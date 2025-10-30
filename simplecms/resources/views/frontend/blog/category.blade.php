@extends('frontend.layouts.app')

@section('title', $category->name . ' - Blog - ' . ($settings['site_name'] ?? 'SimpleCMS'))
@section('meta_description', $category->description ?? 'Browse posts in ' . $category->name . ' category')

@section('content')
    {{-- Page Header --}}
    <section class="bg-primary text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <nav aria-label="breadcrumb" class="mb-3">
                        <ol class="breadcrumb justify-content-center bg-transparent mb-0">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('blog.index') }}" class="text-white">Blog</a></li>
                            <li class="breadcrumb-item active text-white" aria-current="page">{{ $category->name }}</li>
                        </ol>
                    </nav>
                    <h1 class="display-4 fw-bold mb-3">{{ $category->name }}</h1>
                    @if($category->description)
                        <p class="lead">{{ $category->description }}</p>
                    @endif
                    <div class="mt-3">
                        <span class="badge bg-light text-dark">{{ $posts->total() }} {{ Str::plural('Post', $posts->total()) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Posts Content --}}
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
                                                        <img src="{{ asset($post->featured_image) }}" class="img-fluid h-100 w-100" alt="{{ $post->title }}" style="object-fit: cover; min-height: 250px;">
                                                    </a>
                                                </div>
                                            @endif

                                            {{-- Post Content --}}
                                            <div class="{{ $post->featured_image ? 'col-md-8' : 'col-12' }}">
                                                <div class="card-body p-4">
                                                    {{-- Meta Info --}}
                                                    <div class="mb-3">
                                                        <span class="text-muted small">
                                                            <i class="far fa-calendar me-1"></i>{{ $post->published_at->format('M d, Y') }}
                                                        </span>
                                                        <span class="text-muted ms-3 small">
                                                            <i class="far fa-eye me-1"></i>{{ $post->views_count ?? 0 }} views
                                                        </span>
                                                    </div>

                                                    {{-- Title --}}
                                                    <h3 class="card-title mb-3">
                                                        <a href="{{ route('blog.show', $post->slug) }}" class="text-decoration-none text-dark">
                                                            {{ $post->title }}
                                                        </a>
                                                    </h3>

                                                    {{-- Excerpt --}}
                                                    <p class="card-text text-muted mb-3">{{ $post->excerpt }}</p>

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
                            <i class="fas fa-info-circle me-2"></i>No posts found in this category yet.
                        </div>
                        <div class="text-center mt-4">
                            <a href="{{ route('blog.index') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left me-2"></i>Back to All Posts
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Sidebar --}}
                <div class="col-lg-4">
                    {{-- Other Categories Widget --}}
                    @if(isset($otherCategories) && $otherCategories->count() > 0)
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <h5 class="card-title fw-bold mb-3">Other Categories</h5>
                                <ul class="list-unstyled mb-0">
                                    @foreach($otherCategories as $otherCategory)
                                        <li class="mb-2">
                                            <a href="{{ route('blog.category', $otherCategory->slug) }}" class="text-decoration-none d-flex justify-content-between align-items-center">
                                                <span>
                                                    <i class="fas fa-folder me-2 text-primary"></i>{{ $otherCategory->name }}
                                                </span>
                                                <span class="badge bg-light text-dark">{{ $otherCategory->posts_count ?? $otherCategory->posts->count() }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    {{-- Recent Posts Widget --}}
                    @if(isset($recentPosts) && $recentPosts->count() > 0)
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <h5 class="card-title fw-bold mb-3">Recent Posts</h5>
                                <div class="list-group list-group-flush">
                                    @foreach($recentPosts as $recentPost)
                                        <a href="{{ route('blog.show', $recentPost->slug) }}" class="list-group-item list-group-item-action border-0 px-0">
                                            <div class="d-flex">
                                                @if($recentPost->featured_image)
                                                    <img src="{{ asset($recentPost->featured_image) }}" class="me-3 rounded" alt="{{ $recentPost->title }}" style="width: 60px; height: 60px; object-fit: cover;">
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

                    {{-- Search Widget --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-3">Search</h5>
                            <form action="{{ route('blog.index') }}" method="GET">
                                <div class="input-group">
                                    <input type="search" name="search" class="form-control" placeholder="Search posts..." value="{{ request('search') }}">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Category Description Card --}}
                    @if($category->description)
                        <div class="card border-0 shadow-sm bg-light">
                            <div class="card-body">
                                <h5 class="card-title fw-bold mb-3">About This Category</h5>
                                <p class="card-text">{{ $category->description }}</p>
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

    .breadcrumb-item + .breadcrumb-item::before {
        color: rgba(255, 255, 255, 0.7);
    }
</style>
@endpush
