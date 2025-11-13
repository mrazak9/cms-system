@extends('admin.layouts.app')

@section('title', 'Compare Revisions')
@section('page-title', 'Compare Versions: ' . $model->title)

@section('breadcrumb')
    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
    <div class="breadcrumb-item"><a href="{{ route('admin.revisions.index', ['type' => $type, 'id' => $id]) }}">Revision History</a></div>
    <div class="breadcrumb-item active">Compare</div>
@endsection

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-code-branch me-2"></i>Comparing v{{ $revision1->version }} vs v{{ $revision2->version }}</h4>
                    <div class="card-header-action">
                        <a href="{{ route('admin.revisions.index', ['type' => $type, 'id' => $id]) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to History
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5><span class="badge badge-primary">v{{ $revision1->version }}</span></h5>
                                    <p class="mb-1"><strong>Date:</strong> {{ $revision1->created_at->format('M d, Y H:i') }}</p>
                                    <p class="mb-0"><strong>By:</strong> {{ $revision1->user->name ?? 'Unknown' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5><span class="badge badge-success">v{{ $revision2->version }}</span></h5>
                                    <p class="mb-1"><strong>Date:</strong> {{ $revision2->created_at->format('M d, Y H:i') }}</p>
                                    <p class="mb-0"><strong>By:</strong> {{ $revision2->user->name ?? 'Unknown' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(empty($diff))
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>No differences found between these versions.
                        </div>
                    @else
                        <h5 class="mb-3">Differences</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 20%">Field</th>
                                        <th style="width: 40%">
                                            Version {{ $revision1->version }}
                                            <span class="badge badge-primary">Older</span>
                                        </th>
                                        <th style="width: 40%">
                                            Version {{ $revision2->version }}
                                            <span class="badge badge-success">Newer</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($diff as $field => $values)
                                        <tr>
                                            <td><strong>{{ ucfirst(str_replace('_', ' ', $field)) }}</strong></td>
                                            <td style="background-color: #fff3cd;">
                                                @if(is_bool($values['version1']))
                                                    <span class="badge badge-{{ $values['version1'] ? 'success' : 'secondary' }}">
                                                        {{ $values['version1'] ? 'Yes' : 'No' }}
                                                    </span>
                                                @elseif(in_array($field, ['content', 'excerpt']))
                                                    <div style="max-height: 300px; overflow-y: auto; padding: 10px; border: 1px solid #dee2e6; border-radius: 4px;">
                                                        {!! nl2br(e($values['version1'] ?? '-')) !!}
                                                    </div>
                                                @elseif($field === 'published_at' && $values['version1'])
                                                    {{ \Carbon\Carbon::parse($values['version1'])->format('M d, Y H:i') }}
                                                @else
                                                    {{ $values['version1'] ?? '-' }}
                                                @endif
                                            </td>
                                            <td style="background-color: #d4edda;">
                                                @if(is_bool($values['version2']))
                                                    <span class="badge badge-{{ $values['version2'] ? 'success' : 'secondary' }}">
                                                        {{ $values['version2'] ? 'Yes' : 'No' }}
                                                    </span>
                                                @elseif(in_array($field, ['content', 'excerpt']))
                                                    <div style="max-height: 300px; overflow-y: auto; padding: 10px; border: 1px solid #dee2e6; border-radius: 4px;">
                                                        {!! nl2br(e($values['version2'] ?? '-')) !!}
                                                    </div>
                                                @elseif($field === 'published_at' && $values['version2'])
                                                    {{ \Carbon\Carbon::parse($values['version2'])->format('M d, Y H:i') }}
                                                @else
                                                    {{ $values['version2'] ?? '-' }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="alert alert-info mt-4">
                            <strong>Legend:</strong>
                            <span class="badge badge-warning" style="background-color: #fff3cd; color: #856404; border: 1px solid #ffeaa7;">Older Version</span>
                            <span class="badge badge-success" style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;">Newer Version</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
