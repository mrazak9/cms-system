@extends('frontend.layouts.app')

@section('title', 'Blog - ' . ($settings['site_name'] ?? 'SimpleCMS'))
@section('meta_description', 'Browse our latest blog posts and articles')

@section('content')
    {{-- Page Header --}}
    <section class="bg-primary text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-3">Our Blog</h1>
                    <p class="lead">Explore our latest articles, news, and insights</p>
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
                                                            <a href="{{ route('blog.category', $post->category->slug) }}" class="badge bg-primary text-decoration-none">
                                                                {{ $post->category->name }}
                                                            </a>
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
                            <i class="fas fa-info-circle me-2"></i>No blog posts available at the moment.
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

                    {{-- Categories Widget --}}
                    @if(isset($categories) && $categories->count() > 0)
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <h5 class="card-title fw-bold mb-3">Categories</h5>
                                <ul class="list-unstyled mb-0">
                                    @foreach($categories as $category)
                                        <li class="mb-2">
                                            <a href="{{ route('blog.category', $category->slug) }}" class="text-decoration-none d-flex justify-content-between align-items-center">
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
</style>
@endpush
