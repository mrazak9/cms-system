@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumb')
    <div class="breadcrumb-item active">Dashboard</div>
@endsection

@section('content')
    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="far fa-file-alt"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Total Pages</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['total_pages'] ?? 0 }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                    <i class="fas fa-newspaper"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Total Posts</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['total_posts'] ?? 0 }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                    <i class="far fa-user"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Total Users</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['total_users'] ?? 0 }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                    <i class="far fa-images"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Media Files</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['total_media'] ?? 0 }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Posts -->
        <div class="col-lg-8 col-md-12 col-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4>Recent Posts</h4>
                    <div class="card-header-action">
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-primary">View All</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Author</th>
                                    <th>Views</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPosts ?? [] as $post)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.posts.edit', $post->id) }}">
                                                {{ Str::limit($post->title, 40) }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($post->category)
                                                <span class="badge badge-info">{{ $post->category->name }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $post->author->name ?? 'N/A' }}</td>
                                        <td>{{ $post->views ?? 0 }}</td>
                                        <td>
                                            @if($post->is_published)
                                                <span class="badge badge-success">Published</span>
                                            @else
                                                <span class="badge badge-warning">Draft</span>
                                            @endif
                                        </td>
                                        <td>{{ $post->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No posts yet</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Pages -->
        <div class="col-lg-4 col-md-12 col-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4>Recent Pages</h4>
                    <div class="card-header-action">
                        <a href="{{ route('admin.pages.index') }}" class="btn btn-primary">View All</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <ul class="list-unstyled list-unstyled-border">
                        @forelse($recentPages ?? [] as $page)
                            <li class="media">
                                <div class="media-body">
                                    <div class="float-right">
                                        @if($page->is_homepage)
                                            <span class="badge badge-primary">Home</span>
                                        @endif
                                        @if($page->is_published)
                                            <span class="badge badge-success">Published</span>
                                        @else
                                            <span class="badge badge-warning">Draft</span>
                                        @endif
                                    </div>
                                    <div class="media-title">
                                        <a href="{{ route('admin.pages.edit', $page->id) }}">
                                            {{ Str::limit($page->title, 30) }}
                                        </a>
                                    </div>
                                    <div class="text-small text-muted">
                                        {{ $page->created_at->diffForHumans() }}
                                        <div class="bullet"></div>
                                        <span class="text-primary">{{ $page->slug }}</span>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="media">
                                <div class="media-body text-center text-muted">
                                    No pages yet
                                </div>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Activity Chart Placeholder -->
        <div class="col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Activity Overview</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="text-center">
                                <div class="font-weight-600 text-muted mb-2">Published Pages</div>
                                <h4 class="text-primary">{{ $stats['published_pages'] ?? 0 }}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="font-weight-600 text-muted mb-2">Draft Pages</div>
                                <h4 class="text-warning">{{ $stats['draft_pages'] ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-6">
                            <div class="text-center">
                                <div class="font-weight-600 text-muted mb-2">Published Posts</div>
                                <h4 class="text-success">{{ $stats['published_posts'] ?? 0 }}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="font-weight-600 text-muted mb-2">Draft Posts</div>
                                <h4 class="text-danger">{{ $stats['draft_posts'] ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Quick Actions</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <a href="{{ route('admin.pages.create') }}" class="btn btn-primary btn-lg btn-block">
                                <i class="fas fa-plus"></i> New Page
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="{{ route('admin.posts.create') }}" class="btn btn-danger btn-lg btn-block">
                                <i class="fas fa-plus"></i> New Post
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="{{ route('admin.media.index') }}" class="btn btn-success btn-lg btn-block">
                                <i class="fas fa-upload"></i> Upload Media
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="{{ route('admin.settings.index') }}" class="btn btn-warning btn-lg btn-block">
                                <i class="fas fa-cog"></i> Settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
