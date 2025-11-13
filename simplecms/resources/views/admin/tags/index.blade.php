@extends('admin.layouts.app')

@section('title', 'Tags')
@section('page-title', 'Tags Management')

@section('breadcrumb')
    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
    <div class="breadcrumb-item active">Tags</div>
@endsection

@section('content')
    {{-- Statistics --}}
    <div class="row mb-4">
        <div class="col-lg-4">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary"><i class="fas fa-tags"></i></div>
                <div class="card-wrap">
                    <div class="card-header"><h4>Total Tags</h4></div>
                    <div class="card-body">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-statistic-1">
                <div class="card-icon bg-success"><i class="fas fa-check-circle"></i></div>
                <div class="card-wrap">
                    <div class="card-header"><h4>With Posts</h4></div>
                    <div class="card-body">{{ $stats['with_posts'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-statistic-1">
                <div class="card-icon bg-warning"><i class="fas fa-exclamation-circle"></i></div>
                <div class="card-wrap">
                    <div class="card-header"><h4>Empty Tags</h4></div>
                    <div class="card-body">{{ $stats['empty'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>All Tags</h4>
            <div class="card-header-action">
                @can('tags.create')
                <a href="{{ route('admin.tags.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Tag
                </a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <form method="GET" class="form-inline">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search tags..." value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th style="width: 30%">Name</th>
                            <th style="width: 25%">Slug</th>
                            <th style="width: 30%">Description</th>
                            <th class="text-center" style="width: 10%">Posts</th>
                            <th class="text-center" style="width: 5%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tags as $tag)
                            <tr>
                                <td><strong>{{ $tag->name }}</strong></td>
                                <td><code>{{ $tag->slug }}</code></td>
                                <td>{{ Str::limit($tag->description, 50) ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge badge-{{ $tag->posts_count > 0 ? 'success' : 'secondary' }}">
                                        {{ $tag->posts_count }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        @can('tags.edit')
                                        <a href="{{ route('admin.tags.edit', $tag->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endcan
                                        @can('tags.delete')
                                        <form action="{{ route('admin.tags.destroy', $tag->id) }}" method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this tag?');" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">No tags found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($tags->hasPages())
            <div class="card-footer">
                {{ $tags->links() }}
            </div>
        @endif
    </div>
@endsection
