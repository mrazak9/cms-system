@extends('admin.layouts.app')

@section('title', 'Comments')
@section('page-title', 'Comments Management')

@section('breadcrumb')
    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
    <div class="breadcrumb-item active">Comments</div>
@endsection

@section('content')
    {{-- Statistics Cards --}}
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary"><i class="fas fa-comments"></i></div>
                <div class="card-wrap">
                    <div class="card-header"><h4>Total</h4></div>
                    <div class="card-body">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card card-statistic-1">
                <div class="card-icon bg-warning"><i class="fas fa-clock"></i></div>
                <div class="card-wrap">
                    <div class="card-header"><h4>Pending</h4></div>
                    <div class="card-body">{{ $stats['pending'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card card-statistic-1">
                <div class="card-icon bg-success"><i class="fas fa-check"></i></div>
                <div class="card-wrap">
                    <div class="card-header"><h4>Approved</h4></div>
                    <div class="card-body">{{ $stats['approved'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card card-statistic-1">
                <div class="card-icon bg-danger"><i class="fas fa-ban"></i></div>
                <div class="card-wrap">
                    <div class="card-header"><h4>Spam</h4></div>
                    <div class="card-body">{{ $stats['spam'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>All Comments</h4>
            <div class="card-header-form">
                <form method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Search..." value="{{ request('search') }}">
                    <select name="status" class="form-control me-2" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="spam" {{ request('status') === 'spam' ? 'selected' : '' }}>Spam</option>
                    </select>
                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Author</th>
                            <th>Comment</th>
                            <th>Post</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($comments as $comment)
                            <tr>
                                <td>
                                    <strong>{{ $comment->author_name }}</strong><br>
                                    <small class="text-muted">{{ $comment->author_email }}</small>
                                </td>
                                <td>{{ Str::limit($comment->content, 80) }}</td>
                                <td>
                                    <a href="{{ route('blog.show', $comment->post->slug) }}" target="_blank">
                                        {{ Str::limit($comment->post->title, 30) }}
                                    </a>
                                </td>
                                <td>
                                    @if($comment->isPending())
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($comment->isApproved())
                                        <span class="badge badge-success">Approved</span>
                                    @elseif($comment->isSpam())
                                        <span class="badge badge-danger">Spam</span>
                                    @endif
                                </td>
                                <td><small>{{ $comment->created_at->format('M d, Y H:i') }}</small></td>
                                <td>
                                    <div class="btn-group">
                                        @can('comments.moderate')
                                            @if(!$comment->isApproved())
                                                <form action="{{ route('admin.comments.approve', $comment->id) }}" method="POST" class="d-inline">
                                                    @csrf @method('PATCH')
                                                    <button class="btn btn-sm btn-success" title="Approve"><i class="fas fa-check"></i></button>
                                                </form>
                                            @endif
                                            @if(!$comment->isSpam())
                                                <form action="{{ route('admin.comments.spam', $comment->id) }}" method="POST" class="d-inline">
                                                    @csrf @method('PATCH')
                                                    <button class="btn btn-sm btn-warning" title="Spam"><i class="fas fa-ban"></i></button>
                                                </form>
                                            @endif
                                        @endcan
                                        @can('comments.delete')
                                            <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this comment?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-5">No comments found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($comments->hasPages())
            <div class="card-footer">{{ $comments->links() }}</div>
        @endif
    </div>
@endsection
