@extends('admin.layouts.app')

@section('title', 'Posts')
@section('page-title', 'Posts')

@section('breadcrumb')
    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
    <div class="breadcrumb-item active">Posts</div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>All Posts</h4>
                    <div class="card-header-action">
                        @can('posts.create')
                            <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create New Post
                            </a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filter Section -->
                    <form method="GET" action="{{ route('admin.posts.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Filter by Category</label>
                                    <select name="category" class="form-control" onchange="this.form.submit()">
                                        <option value="">All Categories</option>
                                        @foreach($categories ?? [] as $category)
                                            <option value="{{ $category->id }}"
                                                    {{ request('category') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }} ({{ $category->posts_count ?? 0 }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Filter by Status</label>
                                    <select name="status" class="form-control" onchange="this.form.submit()">
                                        <option value="">All Status</option>
                                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Search</label>
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control" placeholder="Search posts..." value="{{ request('search') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Author</th>
                                    <th>Status</th>
                                    <th>Published Date</th>
                                    <th>Views</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($posts as $post)
                                    <tr>
                                        <td>{{ $post->id }}</td>
                                        <td>
                                            <strong>{{ $post->title }}</strong>
                                            @if($post->excerpt)
                                                <br><small class="text-muted">{{ Str::limit($post->excerpt, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($post->category)
                                                <span class="badge badge-info">
                                                    <i class="fas fa-tag"></i> {{ $post->category->name }}
                                                </span>
                                            @else
                                                <span class="text-muted">Uncategorized</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($post->author->name ?? 'N/A') }}&size=32&background=random"
                                                     class="rounded-circle mr-2" width="32" height="32">
                                                <div>{{ $post->author->name ?? 'N/A' }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($post->is_published)
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check"></i> Published
                                                </span>
                                            @else
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-clock"></i> Draft
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($post->published_at)
                                                <div>{{ $post->published_at->format('M d, Y') }}</div>
                                                <small class="text-muted">{{ $post->published_at->format('H:i') }}</small>
                                            @else
                                                <span class="text-muted">Not published</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-light">
                                                <i class="fas fa-eye"></i> {{ $post->views ?? 0 }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                @if($post->is_published)
                                                    <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="btn btn-sm btn-info" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @endif
                                                @can('update', $post)
                                                    <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan
                                                @can('delete', $post)
                                                    <button type="button" class="btn btn-sm btn-danger" title="Delete"
                                                            onclick="deletePost({{ $post->id }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endcan
                                            </div>
                                            @can('delete', $post)
                                                <form id="delete-form-{{ $post->id }}"
                                                      action="{{ route('admin.posts.destroy', $post->id) }}"
                                                      method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            <i class="fas fa-newspaper fa-3x mb-3"></i>
                                            <p>
                                                @if(request()->has('search') || request()->has('category') || request()->has('status'))
                                                    No posts found matching your filters.
                                                    <a href="{{ route('admin.posts.index') }}">Clear filters</a>
                                                @else
                                                    No posts found.
                                                    @can('posts.create')
                                                        <a href="{{ route('admin.posts.create') }}">Create your first post</a>
                                                    @endcan
                                                @endif
                                            </p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($posts->hasPages())
                    <div class="card-footer text-right">
                        {{ $posts->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
<script>
    function deletePost(id) {
        if (confirm('Are you sure you want to delete this post? This action cannot be undone.')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush
