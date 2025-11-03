@extends('admin.layouts.app')

@section('title', 'Roles')
@section('page-title', 'Roles & Permissions')

@section('breadcrumb')
    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
    <div class="breadcrumb-item active">Roles</div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>All Roles</h4>
                    <div class="card-header-action">
                        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New Role
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($roles->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Permissions</th>
                                        <th>Users</th>
                                        <th>Created</th>
                                        <th class="text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($roles as $role)
                                        <tr>
                                            <td>
                                                <strong>{{ ucfirst($role->name) }}</strong>
                                                @if($role->name === 'admin')
                                                    <span class="badge badge-danger ml-1">System</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-primary">
                                                    {{ $role->permissions_count }} permissions
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-info">
                                                    {{ $role->users_count }} users
                                                </span>
                                            </td>
                                            <td>{{ $role->created_at->format('M d, Y') }}</td>
                                            <td class="text-right">
                                                <a href="{{ route('admin.roles.edit', $role->id) }}"
                                                   class="btn btn-sm btn-warning"
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                @if($role->name !== 'admin')
                                                    <button type="button"
                                                            class="btn btn-sm btn-danger"
                                                            onclick="deleteRole({{ $role->id }})"
                                                            title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>

                                                    <form id="delete-form-{{ $role->id }}"
                                                          action="{{ route('admin.roles.destroy', $role->id) }}"
                                                          method="POST"
                                                          style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-user-shield fa-4x text-muted mb-3"></i>
                            <h5>No Roles Found</h5>
                            <p class="text-muted">Create your first role to get started</p>
                            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add New Role
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
    <script>
        function deleteRole(id) {
            if (confirm('Are you sure you want to delete this role? This action cannot be undone.')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
@endpush
