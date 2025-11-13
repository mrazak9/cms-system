@extends('admin.layouts.app')

@section('title', 'SEO Dashboard')

@section('content')
<div class="section-header">
    <h1>SEO Dashboard</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item">SEO</div>
    </div>
</div>

<div class="section-body">
    <!-- SEO Statistics -->
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Posts with Meta</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['posts_with_meta'] }} / {{ $stats['total_posts'] }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-info">
                    <i class="fas fa-file"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Pages with Meta</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['pages_with_meta'] }} / {{ $stats['total_pages'] }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                    <i class="fas fa-random"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Active Redirects</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['active_redirects'] }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                    <i class="fas fa-link"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Total Redirects</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['total_redirects'] }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Posts SEO Analysis -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Recent Posts - SEO Scores</h4>
                    <div class="card-header-action">
                        <a href="{{ route('admin.redirects.index') }}" class="btn btn-primary">
                            <i class="fas fa-random"></i> Manage Redirects
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>SEO Score</th>
                                    <th>Grade</th>
                                    <th>Meta Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentPosts as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ Str::limit($item['post']->title, 50) }}</strong>
                                        </td>
                                        <td>{{ $item['post']->author->name }}</td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                @php
                                                    $score = $item['seo_score'];
                                                    $color = $score >= 80 ? 'success' : ($score >= 60 ? 'warning' : 'danger');
                                                @endphp
                                                <div class="progress-bar bg-{{ $color }}" role="progressbar"
                                                     style="width: {{ $score }}%"
                                                     aria-valuenow="{{ $score }}" aria-valuemin="0" aria-valuemax="100">
                                                    {{ $score }}%
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $gradeColor = match($item['seo_grade']) {
                                                    'A' => 'success',
                                                    'B' => 'info',
                                                    'C' => 'warning',
                                                    default => 'danger'
                                                };
                                            @endphp
                                            <span class="badge badge-{{ $gradeColor }} badge-lg">{{ $item['seo_grade'] }}</span>
                                        </td>
                                        <td>
                                            @if($item['post']->meta_description)
                                                <i class="fas fa-check text-success"></i> Yes
                                            @else
                                                <i class="fas fa-times text-danger"></i> No
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.posts.edit', $item['post']->id) }}"
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SEO Tips -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>SEO Best Practices</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-check-circle text-success"></i> Title Tags</h6>
                            <ul>
                                <li>Keep titles between 30-60 characters</li>
                                <li>Include target keywords near the beginning</li>
                                <li>Make titles unique for each page</li>
                            </ul>

                            <h6><i class="fas fa-check-circle text-success"></i> Meta Descriptions</h6>
                            <ul>
                                <li>Write descriptions between 120-160 characters</li>
                                <li>Include a call-to-action</li>
                                <li>Make them compelling and relevant</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-check-circle text-success"></i> Content</h6>
                            <ul>
                                <li>Aim for at least 300 words per page</li>
                                <li>Use proper heading structure (H1, H2, H3)</li>
                                <li>Include alt text for all images</li>
                                <li>Use internal and external links</li>
                            </ul>

                            <h6><i class="fas fa-check-circle text-success"></i> URLs</h6>
                            <ul>
                                <li>Keep URLs short and descriptive</li>
                                <li>Use hyphens to separate words</li>
                                <li>Avoid special characters and numbers</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
