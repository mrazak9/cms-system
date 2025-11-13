@extends('admin.layouts.app')

@section('title', 'Upcoming Scheduled Content')

@section('content')
<div class="section-header">
    <h1>Upcoming Scheduled Content</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="{{ route('admin.calendar.index') }}">Calendar</a></div>
        <div class="breadcrumb-item">Upcoming</div>
    </div>
</div>

<div class="section-body">
    <!-- Scheduled Posts -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Scheduled Posts</h4>
                </div>
                <div class="card-body p-0">
                    @if($scheduledPosts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Author</th>
                                        <th>Category</th>
                                        <th>Scheduled For</th>
                                        <th>Time Until</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($scheduledPosts as $post)
                                        <tr>
                                            <td>
                                                {{ $post->title }}
                                                <span class="badge {{ $post->getWorkflowStatusBadgeClass() }}">
                                                    {{ $post->getWorkflowStatusLabel() }}
                                                </span>
                                            </td>
                                            <td>{{ $post->author->name }}</td>
                                            <td>{{ $post->category->name ?? 'N/A' }}</td>
                                            <td>{{ $post->scheduled_publish_at->format('M d, Y g:i A') }}</td>
                                            <td>
                                                <span class="text-info">
                                                    {{ $post->scheduled_publish_at->diffForHumans() }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.posts.edit', $post->id) }}"
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            {{ $scheduledPosts->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No scheduled posts found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Scheduled Pages -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Scheduled Pages</h4>
                </div>
                <div class="card-body p-0">
                    @if($scheduledPages->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Creator</th>
                                        <th>Scheduled For</th>
                                        <th>Time Until</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($scheduledPages as $page)
                                        <tr>
                                            <td>
                                                {{ $page->title }}
                                                <span class="badge {{ $page->getWorkflowStatusBadgeClass() }}">
                                                    {{ $page->getWorkflowStatusLabel() }}
                                                </span>
                                            </td>
                                            <td>{{ $page->creator->name ?? 'N/A' }}</td>
                                            <td>{{ $page->scheduled_publish_at->format('M d, Y g:i A') }}</td>
                                            <td>
                                                <span class="text-info">
                                                    {{ $page->scheduled_publish_at->diffForHumans() }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.pages.edit', $page->id) }}"
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            {{ $scheduledPages->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No scheduled pages found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
