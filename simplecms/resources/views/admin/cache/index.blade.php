@extends('admin.layouts.app')

@section('title', 'Cache Management')

@section('content')
<div class="section-header">
    <h1>Cache Management</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item">Cache Management</div>
    </div>
</div>

<div class="section-body">
    <!-- Cache Statistics -->
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="fas fa-server"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Cache Driver</h4>
                    </div>
                    <div class="card-body">
                        {{ ucfirst($stats['driver'] ?? 'Unknown') }}
                    </div>
                </div>
            </div>
        </div>
        @if(isset($stats['size']))
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                    <i class="fas fa-database"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Cache Size</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['size'] }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-info">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Cached Files</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['files'] ?? 0 }}
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Quick Actions</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 mb-3">
                            <form action="{{ route('admin.cache.clear-all') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear all caches?');">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-lg btn-block">
                                    <i class="fas fa-trash"></i>
                                    <div>Clear All Caches</div>
                                    <small>Removes all cached data</small>
                                </button>
                            </form>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <form action="{{ route('admin.cache.warm') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-info btn-lg btn-block">
                                    <i class="fas fa-fire"></i>
                                    <div>Warm Up Caches</div>
                                    <small>Preload frequently used data</small>
                                </button>
                            </form>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <form action="{{ route('admin.cache.optimize') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-lg btn-block">
                                    <i class="fas fa-rocket"></i>
                                    <div>Optimize Application</div>
                                    <small>Config, routes & views</small>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Selective Cache Clearing -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Clear Specific Caches</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        @php
                            $cacheTags = [
                                'settings' => ['name' => 'Settings', 'icon' => 'fa-cog', 'color' => 'primary'],
                                'menus' => ['name' => 'Menus', 'icon' => 'fa-bars', 'color' => 'info'],
                                'categories' => ['name' => 'Categories', 'icon' => 'fa-tags', 'color' => 'warning'],
                                'posts' => ['name' => 'Posts', 'icon' => 'fa-newspaper', 'color' => 'success'],
                                'pages' => ['name' => 'Pages', 'icon' => 'fa-file', 'color' => 'secondary'],
                            ];
                        @endphp

                        @foreach($cacheTags as $tag => $info)
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                            <form action="{{ route('admin.cache.clear-tag') }}" method="POST">
                                @csrf
                                <input type="hidden" name="tag" value="{{ $tag }}">
                                <button type="submit" class="btn btn-outline-{{ $info['color'] }} btn-block">
                                    <i class="fas {{ $info['icon'] }}"></i>
                                    <div>{{ $info['name'] }}</div>
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Information -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>About Caching</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> Performance Tips</h6>
                        <ul class="mb-0">
                            <li><strong>Clear All Caches:</strong> Use when you've made significant changes to code or database</li>
                            <li><strong>Warm Up Caches:</strong> Run after clearing caches to preload frequently accessed data</li>
                            <li><strong>Optimize:</strong> Best used before deployment to production for maximum performance</li>
                            <li><strong>Specific Cache Tags:</strong> Clear only the cache related to your changes (e.g., clear "Posts" cache after publishing)</li>
                        </ul>
                    </div>

                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle"></i> Important Notes</h6>
                        <ul class="mb-0">
                            <li>Clearing caches may temporarily slow down your site until caches are rebuilt</li>
                            <li>Configuration and route caching should be cleared when making changes to config files or routes</li>
                            <li>For production environments, consider using Redis or Memcached as cache driver for better performance</li>
                            <li>Current driver: <strong>{{ ucfirst($stats['driver'] ?? 'file') }}</strong></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
