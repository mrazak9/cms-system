@extends('admin.layouts.app')

@section('title', 'URL Redirects')

@section('content')
<div class="section-header">
    <h1>URL Redirects</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item active"><a href="{{ route('admin.seo.index') }}">SEO</a></div>
        <div class="breadcrumb-item">Redirects</div>
    </div>
</div>

<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>All Redirects</h4>
                    <div class="card-header-action">
                        <a href="{{ route('admin.redirects.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Redirect
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" class="mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Search URLs..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>From URL</th>
                                    <th>To URL</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Hits</th>
                                    <th>Last Used</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($redirects as $redirect)
                                    <tr>
                                        <td><code>{{ $redirect->from_url }}</code></td>
                                        <td><code>{{ Str::limit($redirect->to_url, 50) }}</code></td>
                                        <td>
                                            <span class="badge badge-{{ $redirect->status_code == 301 ? 'success' : 'info' }}">
                                                {{ $redirect->status_code }} {{ $redirect->status_code == 301 ? 'Permanent' : 'Temporary' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($redirect->is_active)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>{{ number_format($redirect->hits) }}</td>
                                        <td>{{ $redirect->last_used_at ? $redirect->last_used_at->diffForHumans() : 'Never' }}</td>
                                        <td>
                                            <a href="{{ route('admin.redirects.edit', $redirect) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.redirects.destroy', $redirect) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No redirects found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $redirects->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
