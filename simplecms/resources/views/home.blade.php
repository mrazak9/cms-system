@extends('frontend.layouts.app')

@section('title', 'Home - ' . config('app.name'))

@section('content')
<section class="section">
    <div class="container">
    <!-- Hero Section -->
    <div class="jumbotron bg-light">
        <h1 class="display-4">Welcome to {{ config('app.name') }}</h1>
        <p class="lead">A powerful and flexible content management system built with Laravel.</p>
        <hr class="my-4">
        <p>Manage your content with ease and flexibility. Create pages, posts, menus, and more!</p>
        @guest
            <a class="btn btn-primary btn-lg" href="{{ route('register') }}" role="button">
                <i class="fas fa-rocket mr-2"></i> Get Started
            </a>
            <a class="btn btn-outline-secondary btn-lg ml-2" href="{{ route('login') }}" role="button">
                <i class="fas fa-sign-in-alt mr-2"></i> Login
            </a>
        @else
            <a class="btn btn-primary btn-lg" href="{{ route('admin.dashboard') }}" role="button">
                <i class="fas fa-tachometer-alt mr-2"></i> Go to Dashboard
            </a>
        @endguest
    </div>

    <!-- Latest Posts -->
    @if($posts && $posts->count() > 0)
        <section class="my-5">
            <h2 class="mb-4">Latest Posts</h2>
            <div class="row">
                @foreach($posts as $post)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            @if($post->featured_image)
                                <img src="{{ asset('storage/' . $post->featured_image) }}" class="card-img-top" alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="fas fa-image fa-3x text-white-50"></i>
                                </div>
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $post->title }}</h5>
                                <p class="card-text text-muted">{{ Str::limit(strip_tags($post->content), 100) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="far fa-calendar mr-1"></i> {{ $post->published_at->format('M d, Y') }}
                                    </small>
                                    @if($post->category)
                                        <span class="badge badge-primary">{{ $post->category->name }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer bg-transparent">
                                <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-sm btn-outline-primary btn-block">
                                    Read More <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Features Section -->
    <section class="my-5">
        <h2 class="mb-4 text-center">Features</h2>
        <div class="row">
            <div class="col-md-4 text-center mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-file-alt fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title">Pages & Posts</h5>
                        <p class="card-text text-muted">Create and manage unlimited pages and blog posts with rich content editor.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-center mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-bars fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title">Menu Management</h5>
                        <p class="card-text text-muted">Build custom navigation menus with drag & drop interface and nested support.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-center mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-images fa-3x text-info"></i>
                        </div>
                        <h5 class="card-title">Media Library</h5>
                        <p class="card-text text-muted">Upload and organize your images and files with intuitive media manager.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </div>
</section>
@endsection
