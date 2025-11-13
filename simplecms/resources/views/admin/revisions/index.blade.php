@extends('admin.layouts.app')

@section('title', 'Revision History')
@section('page-title', 'Revision History: ' . $model->title)

@section('breadcrumb')
    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
    @if($type === 'post')
        <div class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">Posts</a></div>
        <div class="breadcrumb-item"><a href="{{ route('admin.posts.edit', $id) }}">Edit Post</a></div>
    @elseif($type === 'page')
        <div class="breadcrumb-item"><a href="{{ route('admin.pages.index') }}">Pages</a></div>
        <div class="breadcrumb-item"><a href="{{ route('admin.pages.edit', $id) }}">Edit Page</a></div>
    @endif
    <div class="breadcrumb-item active">Revision History</div>
@endsection

@section('content')
    {{-- Info Card --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="text-white mb-2"><i class="fas fa-history me-2"></i>{{ $model->title }}</h5>
                            <p class="mb-0">
                                <strong>Current Version:</strong> {{ $model->getCurrentVersion() }} |
                                <strong>Total Revisions:</strong> {{ $model->getRevisionsCount() }}
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            @if($type === 'post')
                                <a href="{{ route('admin.posts.edit', $id) }}" class="btn btn-light">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Edit
                                </a>
                            @elseif($type === 'page')
                                <a href="{{ route('admin.pages.edit', $id) }}" class="btn btn-light">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Edit
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Compare Form --}}
    @if($revisions->count() > 1)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-code-branch me-2"></i>Compare Versions</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.revisions.compare', ['type' => $type, 'id' => $id]) }}" method="GET" class="form-inline">
                            <div class="row align-items-end">
                                <div class="col-md-4">
                                    <label>Version 1</label>
                                    <select name="version1" class="form-control" required>
                                        <option value="">Select Version</option>
                                        @foreach($revisions as $rev)
                                            <option value="{{ $rev->version }}">
                                                v{{ $rev->version }} - {{ $rev->created_at->format('M d, Y H:i') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Version 2</label>
                                    <select name="version2" class="form-control" required>
                                        <option value="">Select Version</option>
                                        @foreach($revisions as $rev)
                                            <option value="{{ $rev->version }}">
                                                v{{ $rev->version }} - {{ $rev->created_at->format('M d, Y H:i') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-code-branch me-2"></i>Compare Versions
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Revisions List --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-list me-2"></i>All Revisions</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 10%">Version</th>
                                    <th style="width: 15%">Date & Time</th>
                                    <th style="width: 15%">User</th>
                                    <th style="width: 30%">Changes</th>
                                    <th style="width: 15%">Description</th>
                                    <th class="text-center" style="width: 15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($revisions as $revision)
                                    <tr class="{{ $revision->version == $model->getCurrentVersion() ? 'table-success' : '' }}">
                                        <td>
                                            <strong>v{{ $revision->version }}</strong>
                                            @if($revision->version == $model->getCurrentVersion())
                                                <br><span class="badge badge-success">Current</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $revision->created_at->format('M d, Y') }}
                                            <br>
                                            <small class="text-muted">{{ $revision->created_at->format('H:i:s') }}</small>
                                            <br>
                                            <small class="text-muted">{{ $revision->created_at->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            @if($revision->user)
                                                {{ $revision->user->name }}
                                                <br>
                                                <small class="text-muted">{{ $revision->user->email }}</small>
                                            @else
                                                <span class="text-muted">Unknown</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $changes = $revision->getChanges();
                                            @endphp
                                            @if(!empty($changes))
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach(array_keys($changes) as $field)
                                                        <span class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $field)) }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted">No changes</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $revision->description ?? '-' }}
                                            <br>
                                            <small class="text-muted">{{ $revision->ip_address }}</small>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.revisions.show', ['type' => $type, 'id' => $id, 'version' => $revision->version]) }}"
                                                   class="btn btn-sm btn-info"
                                                   title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($revision->version != $model->getCurrentVersion())
                                                    @can('revisions.restore')
                                                        <form action="{{ route('admin.revisions.restore', ['type' => $type, 'id' => $id, 'version' => $revision->version]) }}"
                                                              method="POST"
                                                              onsubmit="return confirm('Are you sure you want to restore to version {{ $revision->version }}? This will create a new version.');"
                                                              class="d-inline">
                                                            @csrf
                                                            @method('POST')
                                                            <button type="submit" class="btn btn-sm btn-warning" title="Restore">
                                                                <i class="fas fa-undo"></i>
                                                            </button>
                                                        </form>
                                                    @endcan
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-5">
                                            No revisions found. Revisions will be created when you edit this content.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($revisions->hasPages())
                    <div class="card-footer">
                        {{ $revisions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .table-success {
        background-color: #d4edda !important;
    }
</style>
@endpush
