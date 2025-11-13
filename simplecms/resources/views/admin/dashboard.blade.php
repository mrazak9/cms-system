@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumb')
    <div class="breadcrumb-item active">Dashboard</div>
@endsection

@section('content')
    <!-- Statistics Cards -->
    <div class="row">
        @if(auth()->user()->hasRole('author'))
            <!-- Author-specific stats -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>My Posts</h4>
                        </div>
                        <div class="card-body">
                            {{ $stats['my_posts'] ?? 0 }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Published</h4>
                        </div>
                        <div class="card-body">
                            {{ $stats['my_published_posts'] ?? 0 }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Drafts</h4>
                        </div>
                        <div class="card-body">
                            {{ $stats['my_draft_posts'] ?? 0 }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-info">
                        <i class="far fa-images"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>My Media</h4>
                        </div>
                        <div class="card-body">
                            {{ $stats['my_media'] ?? 0 }}
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Admin/Editor stats -->
            @can('pages.view')
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
            @endcan

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

            @can('users.view')
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
            @endcan

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
        @endif
    </div>

    <!-- Activity Stats Row -->
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="card card-statistic-2">
                <div class="card-stats">
                    <div class="card-stats-title">Content Statistics</div>
                    <div class="card-stats-items">
                        <div class="card-stats-item">
                            <div class="card-stats-item-count">{{ $stats['published_posts'] ?? 0 }}</div>
                            <div class="card-stats-item-label">Published</div>
                        </div>
                        <div class="card-stats-item">
                            <div class="card-stats-item-count">{{ $stats['draft_posts'] ?? 0 }}</div>
                            <div class="card-stats-item-label">Drafts</div>
                        </div>
                    </div>
                </div>
                <div class="card-icon shadow-primary bg-primary">
                    <i class="fas fa-newspaper"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Posts</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['total_posts'] ?? 0 }}
                    </div>
                </div>
            </div>
        </div>

        @can('pages.view')
        <div class="col-lg-3 col-md-6">
            <div class="card card-statistic-2">
                <div class="card-stats">
                    <div class="card-stats-title">Page Statistics</div>
                    <div class="card-stats-items">
                        <div class="card-stats-item">
                            <div class="card-stats-item-count">{{ $stats['published_pages'] ?? 0 }}</div>
                            <div class="card-stats-item-label">Published</div>
                        </div>
                        <div class="card-stats-item">
                            <div class="card-stats-item-count">{{ $stats['draft_pages'] ?? 0 }}</div>
                            <div class="card-stats-item-label">Drafts</div>
                        </div>
                    </div>
                </div>
                <div class="card-icon shadow-primary bg-info">
                    <i class="far fa-file-alt"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Pages</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['total_pages'] ?? 0 }}
                    </div>
                </div>
            </div>
        </div>
        @endcan

        <div class="col-lg-3 col-md-6">
            <div class="card card-statistic-2">
                <div class="card-stats">
                    <div class="card-stats-title">This Week</div>
                    <div class="card-stats-items">
                        <div class="card-stats-item">
                            <div class="card-stats-item-count">{{ $stats['posts_this_week'] ?? 0 }}</div>
                            <div class="card-stats-item-label">New Posts</div>
                        </div>
                    </div>
                </div>
                <div class="card-icon shadow-primary bg-success">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Activity</h4>
                    </div>
                    <div class="card-body">
                        Last 7 Days
                    </div>
                </div>
            </div>
        </div>

        @can('categories.view')
        <div class="col-lg-3 col-md-6">
            <div class="card card-statistic-2">
                <div class="card-icon shadow-primary bg-warning">
                    <i class="fas fa-tags"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Categories</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['total_categories'] ?? 0 }}
                    </div>
                </div>
            </div>
        </div>
        @endcan
    </div>

    <div class="row">
        <!-- Recent Posts -->
        <div class="col-lg-8 col-md-12 col-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4>
                        @if(auth()->user()->hasRole('author') && !auth()->user()->can('posts.edit-all'))
                            My Recent Posts
                        @else
                            Recent Posts
                        @endif
                    </h4>
                    <div class="card-header-action">
                        @can('posts.view')
                            <a href="{{ route('admin.posts.index') }}" class="btn btn-primary">View All</a>
                        @endcan
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Category</th>
                                    @if(!auth()->user()->hasRole('author'))
                                        <th>Author</th>
                                    @endif
                                    <th>Views</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPosts ?? [] as $post)
                                    <tr>
                                        <td>
                                            @can('update', $post)
                                                <a href="{{ route('admin.posts.edit', $post->id) }}">
                                                    {{ Str::limit($post->title, 40) }}
                                                </a>
                                            @else
                                                {{ Str::limit($post->title, 40) }}
                                            @endcan
                                        </td>
                                        <td>
                                            @if($post->category)
                                                <span class="badge badge-info">{{ $post->category->name }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        @if(!auth()->user()->hasRole('author'))
                                            <td>{{ $post->author->name ?? 'N/A' }}</td>
                                        @endif
                                        <td>
                                            <span class="badge badge-light">
                                                <i class="fas fa-eye"></i> {{ $post->views_count ?? 0 }}
                                            </span>
                                        </td>
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
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="fas fa-newspaper fa-3x mb-3"></i>
                                            <p>No posts yet</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Pages & Quick Actions -->
        <div class="col-lg-4 col-md-12 col-12 col-sm-12">
            @can('pages.view')
            <div class="card">
                <div class="card-header">
                    <h4>Recent Pages</h4>
                    <div class="card-header-action">
                        <a href="{{ route('admin.pages.index') }}" class="btn btn-primary btn-sm">View All</a>
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
                                <div class="media-body text-center text-muted py-3">
                                    No pages yet
                                </div>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
            @endcan

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h4>Quick Actions</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        @can('pages.create')
                        <div class="col-6 mb-3">
                            <a href="{{ route('admin.pages.create') }}" class="btn btn-primary btn-lg btn-block">
                                <i class="fas fa-plus"></i> New Page
                            </a>
                        </div>
                        @endcan

                        @can('posts.create')
                        <div class="col-6 mb-3">
                            <a href="{{ route('admin.posts.create') }}" class="btn btn-danger btn-lg btn-block">
                                <i class="fas fa-plus"></i> New Post
                            </a>
                        </div>
                        @endcan

                        @can('media.upload')
                        <div class="col-6 mb-3">
                            <a href="{{ route('admin.media.index') }}" class="btn btn-success btn-lg btn-block">
                                <i class="fas fa-upload"></i> Upload Media
                            </a>
                        </div>
                        @endcan

                        @can('settings.view')
                        <div class="col-6 mb-3">
                            <a href="{{ route('admin.settings.index') }}" class="btn btn-warning btn-lg btn-block">
                                <i class="fas fa-cog"></i> Settings
                            </a>
                        </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Popular Posts & Recent Activity -->
    <div class="row">
        <!-- Popular Posts -->
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>
                        <i class="fas fa-fire text-danger"></i> Popular Posts
                    </h4>
                </div>
                <div class="card-body p-0">
                    <ul class="list-unstyled list-unstyled-border">
                        @forelse($popularPosts ?? [] as $post)
                            <li class="media">
                                <div class="media-body">
                                    <div class="float-right">
                                        <span class="badge badge-primary">
                                            <i class="fas fa-eye"></i> {{ $post->views_count ?? 0 }}
                                        </span>
                                    </div>
                                    <div class="media-title">
                                        @can('update', $post)
                                            <a href="{{ route('admin.posts.edit', $post->id) }}">
                                                {{ Str::limit($post->title, 40) }}
                                            </a>
                                        @else
                                            {{ Str::limit($post->title, 40) }}
                                        @endcan
                                    </div>
                                    <div class="text-small text-muted">
                                        By {{ $post->author->name ?? 'Unknown' }}
                                        <div class="bullet"></div>
                                        {{ $post->published_at ? $post->published_at->diffForHumans() : 'Not published' }}
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="media">
                                <div class="media-body text-center text-muted py-4">
                                    <i class="fas fa-newspaper fa-3x mb-3"></i>
                                    <p>No published posts yet</p>
                                </div>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Recent Users (Admin only) / Recent Media -->
        <div class="col-lg-6 col-md-12">
            @can('users.view')
            <div class="card">
                <div class="card-header">
                    <h4>
                        <i class="fas fa-users text-primary"></i> Recent Users
                    </h4>
                    <div class="card-header-action">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-sm">View All</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <ul class="list-unstyled list-unstyled-border">
                        @forelse($recentUsers ?? [] as $user)
                            <li class="media">
                                <img class="mr-3 rounded-circle" width="50"
                                     src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=50&background=random"
                                     alt="{{ $user->name }}">
                                <div class="media-body">
                                    <div class="float-right">
                                        @foreach($user->roles as $role)
                                            @php
                                                $badgeClass = match($role->name) {
                                                    'admin' => 'badge-danger',
                                                    'editor' => 'badge-warning',
                                                    'author' => 'badge-success',
                                                    default => 'badge-secondary'
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">
                                                {{ ucfirst($role->name) }}
                                            </span>
                                        @endforeach
                                    </div>
                                    <div class="media-title font-weight-600">
                                        <a href="{{ route('admin.users.edit', $user->id) }}">
                                            {{ $user->name }}
                                        </a>
                                    </div>
                                    <div class="text-small text-muted">
                                        {{ $user->email }}
                                        <div class="bullet"></div>
                                        {{ $user->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="media">
                                <div class="media-body text-center text-muted py-3">
                                    No recent users
                                </div>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
            @else
            <!-- Recent Media for Authors/Editors -->
            <div class="card">
                <div class="card-header">
                    <h4>
                        <i class="far fa-images text-success"></i>
                        @if(auth()->user()->hasRole('author') && !auth()->user()->can('media.edit-all'))
                            My Recent Media
                        @else
                            Recent Media
                        @endif
                    </h4>
                    <div class="card-header-action">
                        <a href="{{ route('admin.media.index') }}" class="btn btn-success btn-sm">View All</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <ul class="list-unstyled list-unstyled-border">
                        @forelse($recentMedia ?? [] as $media)
                            <li class="media">
                                <div class="media-body">
                                    <div class="float-right">
                                        <span class="badge badge-light">
                                            {{ $media->formatted_file_size ?? formatBytes($media->file_size ?? 0) }}
                                        </span>
                                    </div>
                                    <div class="media-title">
                                        {{ Str::limit($media->filename, 40) }}
                                    </div>
                                    <div class="text-small text-muted">
                                        By {{ $media->uploader->name ?? 'Unknown' }}
                                        <div class="bullet"></div>
                                        {{ $media->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="media">
                                <div class="media-body text-center text-muted py-3">
                                    No media files yet
                                </div>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
            @endcan
        </div>
    </div>

    <!-- Post Activity Chart -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Posts Activity (Last 7 Days)</h4>
                </div>
                <div class="card-body">
                    <canvas id="postsChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    // Posts Activity Chart
    var ctx = document.getElementById('postsChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['labels'] ?? []) !!},
            datasets: [{
                label: 'Posts Created',
                data: {!! json_encode($chartData['data'] ?? []) !!},
                borderColor: 'rgb(99, 110, 250)',
                backgroundColor: 'rgba(99, 110, 250, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endpush
