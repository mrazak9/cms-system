@extends('admin.layouts.app')

@section('title', 'Pages')
@section('page-title', 'Pages')

@section('breadcrumb')
    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
    <div class="breadcrumb-item active">Pages</div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>All Pages</h4>
                    <div class="card-header-action">
                        <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Create New Page
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Slug</th>
                                    <th>Theme</th>
                                    <th>Status</th>
                                    <th>Homepage</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pages as $page)
                                    <tr>
                                        <td>{{ $page->id }}</td>
                                        <td>
                                            <strong>{{ $page->title }}</strong>
                                            @if($page->meta_description)
                                                <br><small class="text-muted">{{ Str::limit($page->meta_description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <code>{{ $page->slug }}</code>
                                        </td>
                                        <td>
                                            @if($page->theme)
                                                <span class="badge badge-info">{{ $page->theme->name }}</span>
                                            @else
                                                <span class="text-muted">Default</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($page->is_published)
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
                                            @if($page->is_homepage)
                                                <span class="badge badge-primary">
                                                    <i class="fas fa-home"></i> Home
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>{{ $page->created_at->format('M d, Y') }}</div>
                                            <small class="text-muted">{{ $page->created_at->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                @if($page->is_published)
                                                    <a href="{{ url($page->slug) }}" target="_blank" class="btn btn-sm btn-info" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @endif
                                                <a href="{{ route('admin.pages.edit', $page->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" title="Delete"
                                                        onclick="deletePage({{ $page->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                            <form id="delete-form-{{ $page->id }}"
                                                  action="{{ route('admin.pages.destroy', $page->id) }}"
                                                  method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            <i class="fas fa-file-alt fa-3x mb-3"></i>
                                            <p>No pages found. <a href="{{ route('admin.pages.create') }}">Create your first page</a></p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($pages->hasPages())
                    <div class="card-footer text-right">
                        {{ $pages->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
<script>
    function deletePage(id) {
        if (confirm('Are you sure you want to delete this page? This action cannot be undone.')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush
