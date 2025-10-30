@extends('frontend.layouts.app')

@section('title', $homepage->title ?? $siteName ?? 'SimpleCMS')
@section('meta_description', $homepage->meta_description ?? $siteDescription ?? '')
@section('meta_keywords', $homepage->meta_keywords ?? '')

@section('content')
    {{-- Render Page Sections if Homepage Exists --}}
    @if(isset($homepage) && $homepage->sections->count() > 0)
        @foreach($homepage->sections as $section)
            @if($section->is_visible && $section->sectionTemplate)
                @include('frontend.components.sections.' . $section->sectionTemplate->blade_view, [
                    'section' => $section,
                    'content' => array_merge(
                        json_decode($section->sectionTemplate->default_fields, true) ?? [],
                        $section->content ?? []
                    )
                ])
            @endif
        @endforeach
    @else
        {{-- Default Hero Section --}}
        <section class="hero-section bg-primary text-white py-5">
            <div class="container py-5">
                <div class="row align-items-center">
                    <div class="col-lg-8 mx-auto text-center">
                        <h1 class="display-3 fw-bold mb-4">Welcome to {{ $siteName ?? 'SimpleCMS' }}</h1>
                        <p class="lead mb-4">{{ $siteDescription ?? 'A powerful and flexible content management system built with Laravel.' }}</p>
                        <div class="d-flex gap-3 justify-content-center">
                            <a href="{{ route('blog.index') }}" class="btn btn-light btn-lg">
                                <i class="fas fa-blog me-2"></i>Read Our Blog
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- Latest Blog Posts Section --}}
    @if(isset($latestPosts) && $latestPosts->count() > 0)
        <section class="section bg-light">
            <div class="container">
                <div class="row mb-4">
                    <div class="col-12 text-center">
                        <h2 class="fw-bold mb-3">Latest Blog Posts</h2>
                        <p class="text-muted">Stay updated with our latest articles and insights</p>
                    </div>
                </div>

                <div class="row g-4">
                    @foreach($latestPosts as $post)
                        <div class="col-lg-4 col-md-6">
                            <div class="card h-100 shadow-sm border-0 hover-lift">
                                @if($post->featured_image)
                                    <img src="{{ asset($post->featured_image) }}" class="card-img-top" alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="fas fa-image fa-3x text-white opacity-50"></i>
                                    </div>
                                @endif

                                <div class="card-body d-flex flex-column">
                                    <div class="mb-2">
                                        @if($post->category)
                                            <span class="badge bg-primary">{{ $post->category->name }}</span>
                                        @endif
                                        <small class="text-muted ms-2">
                                            <i class="far fa-calendar me-1"></i>{{ $post->published_at->format('M d, Y') }}
                                        </small>
                                    </div>

                                    <h5 class="card-title mb-3">
                                        <a href="{{ route('blog.show', $post->slug) }}" class="text-decoration-none text-dark">
                                            {{ $post->title }}
                                        </a>
                                    </h5>

                                    <p class="card-text text-muted flex-grow-1">{{ Str::limit($post->excerpt, 120) }}</p>

                                    <div class="mt-3">
                                        <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-outline-primary btn-sm">
                                            Read More <i class="fas fa-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="row mt-5">
                    <div class="col-12 text-center">
                        <a href="{{ route('blog.index') }}" class="btn btn-primary btn-lg">
                            View All Posts <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- Featured Posts Section --}}
    @if(isset($featuredPosts) && $featuredPosts->count() > 0)
        <section class="section">
            <div class="container">
                <div class="row mb-4">
                    <div class="col-12 text-center">
                        <h2 class="fw-bold mb-3">Featured Articles</h2>
                        <p class="text-muted">Our most popular and trending content</p>
                    </div>
                </div>

                <div class="row g-4">
                    @foreach($featuredPosts as $post)
                        <div class="col-lg-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="row g-0">
                                    @if($post->featured_image)
                                        <div class="col-md-5">
                                            <img src="{{ asset($post->featured_image) }}" class="img-fluid h-100 w-100" alt="{{ $post->title }}" style="object-fit: cover;">
                                        </div>
                                    @endif
                                    <div class="{{ $post->featured_image ? 'col-md-7' : 'col-12' }}">
                                        <div class="card-body">
                                            @if($post->category)
                                                <span class="badge bg-primary mb-2">{{ $post->category->name }}</span>
                                            @endif
                                            <h5 class="card-title">
                                                <a href="{{ route('blog.show', $post->slug) }}" class="text-decoration-none text-dark">
                                                    {{ $post->title }}
                                                </a>
                                            </h5>
                                            <p class="card-text text-muted">{{ Str::limit($post->excerpt, 100) }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    <i class="far fa-calendar me-1"></i>{{ $post->published_at->format('M d, Y') }}
                                                </small>
                                                <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-sm btn-outline-primary">
                                                    Read More
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Categories Section --}}
    @if(isset($categories) && $categories->count() > 0)
        <section class="section bg-light">
            <div class="container">
                <div class="row mb-4">
                    <div class="col-12 text-center">
                        <h2 class="fw-bold mb-3">Browse by Category</h2>
                        <p class="text-muted">Explore content organized by topics</p>
                    </div>
                </div>

                <div class="row g-3">
                    @foreach($categories as $category)
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <a href="{{ route('blog.category', $category->slug) }}" class="text-decoration-none">
                                <div class="card border-0 shadow-sm h-100 text-center p-4 hover-lift">
                                    <div class="card-body">
                                        <i class="fas fa-folder fa-3x text-primary mb-3"></i>
                                        <h5 class="card-title mb-2">{{ $category->name }}</h5>
                                        @if($category->description)
                                            <p class="card-text text-muted small">{{ Str::limit($category->description, 60) }}</p>
                                        @endif
                                        <span class="badge bg-primary">{{ $category->posts->count() }} Posts</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Call to Action Section --}}
    <section class="section bg-primary text-white">
        <div class="container text-center">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <h2 class="fw-bold mb-3">Ready to Get Started?</h2>
                    <p class="lead mb-4">Join our community and stay updated with the latest content and updates.</p>
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="{{ route('blog.index') }}" class="btn btn-light btn-lg">
                            Explore Blog
                        </a>
                    </div>
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
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }

    .hero-section {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    }
</style>
@endpush
