@extends('admin.layouts.app')

@section('title', 'Revision Details')
@section('page-title', 'Revision v' . $revision->version . ': ' . $model->title)

@section('breadcrumb')
    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
    <div class="breadcrumb-item"><a href="{{ route('admin.revisions.index', ['type' => $type, 'id' => $id]) }}">Revision History</a></div>
    <div class="breadcrumb-item active">v{{ $revision->version }}</div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-code-branch me-2"></i>Revision v{{ $revision->version }}</h4>
                    <div class="card-header-action">
                        <a href="{{ route('admin.revisions.index', ['type' => $type, 'id' => $id]) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to History
                        </a>
                        @if($revision->version != $model->getCurrentVersion())
                            @can('revisions.restore')
                                <form action="{{ route('admin.revisions.restore', ['type' => $type, 'id' => $id, 'version' => $revision->version]) }}"
                                      method="POST"
                                      onsubmit="return confirm('Are you sure you want to restore to this version?');"
                                      class="d-inline">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-undo me-2"></i>Restore to This Version
                                    </button>
                                </form>
                            @endcan
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    {{-- Metadata --}}
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <strong>Version:</strong><br>
                            <span class="badge badge-lg badge-primary">v{{ $revision->version }}</span>
                            @if($revision->version == $model->getCurrentVersion())
                                <span class="badge badge-success">Current</span>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <strong>Date & Time:</strong><br>
                            {{ $revision->created_at->format('M d, Y H:i:s') }}<br>
                            <small class="text-muted">{{ $revision->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="col-md-3">
                            <strong>Modified By:</strong><br>
                            @if($revision->user)
                                {{ $revision->user->name }}<br>
                                <small class="text-muted">{{ $revision->user->email }}</small>
                            @else
                                <span class="text-muted">Unknown</span>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <strong>IP Address:</strong><br>
                            {{ $revision->ip_address ?? '-' }}
                        </div>
                    </div>

                    @if($revision->description)
                        <div class="alert alert-info">
                            <strong>Description:</strong> {{ $revision->description }}
                        </div>
                    @endif

                    {{-- Changes Summary --}}
                    @php
                        $changes = $revision->getChanges();
                    @endphp

                    @if(!empty($changes))
                        <h5 class="mb-3">Changes in This Revision</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 20%">Field</th>
                                        <th style="width: 40%">Old Value</th>
                                        <th style="width: 40%">New Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($changes as $field => $change)
                                        <tr>
                                            <td><strong>{{ ucfirst(str_replace('_', ' ', $field)) }}</strong></td>
                                            <td>
                                                @if(is_bool($change['old']))
                                                    <span class="badge badge-{{ $change['old'] ? 'success' : 'secondary' }}">
                                                        {{ $change['old'] ? 'Yes' : 'No' }}
                                                    </span>
                                                @elseif($field === 'content' || $field === 'excerpt')
                                                    <div style="max-height: 200px; overflow-y: auto; background: #f8f9fa; padding: 10px; border-radius: 4px;">
                                                        {!! nl2br(e($change['old'] ?? '-')) !!}
                                                    </div>
                                                @elseif($field === 'published_at' && $change['old'])
                                                    {{ \Carbon\Carbon::parse($change['old'])->format('M d, Y H:i') }}
                                                @else
                                                    {{ $change['old'] ?? '-' }}
                                                @endif
                                            </td>
                                            <td>
                                                @if(is_bool($change['new']))
                                                    <span class="badge badge-{{ $change['new'] ? 'success' : 'secondary' }}">
                                                        {{ $change['new'] ? 'Yes' : 'No' }}
                                                    </span>
                                                @elseif($field === 'content' || $field === 'excerpt')
                                                    <div style="max-height: 200px; overflow-y: auto; background: #d4edda; padding: 10px; border-radius: 4px;">
                                                        {!! nl2br(e($change['new'] ?? '-')) !!}
                                                    </div>
                                                @elseif($field === 'published_at' && $change['new'])
                                                    {{ \Carbon\Carbon::parse($change['new'])->format('M d, Y H:i') }}
                                                @else
                                                    {{ $change['new'] ?? '-' }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            No changes detected in this revision.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
