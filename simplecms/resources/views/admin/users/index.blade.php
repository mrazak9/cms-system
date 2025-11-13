@extends('admin.layouts.app')

@section('title', 'Users')
@section('page-title', 'Users Management')

@section('breadcrumb')
    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
    <div class="breadcrumb-item active">Users</div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>All Users</h4>
                    <div class="card-header-action">
                        @can('users.create')
                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add New User
                            </a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Joined</th>
                                        <th class="text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>
                                                <strong>{{ $user->name }}</strong>
                                                @if($user->id === auth()->id())
                                                    <span class="badge badge-info ml-1">You</span>
                                                @endif
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @foreach($user->roles as $role)
                                                    @php
                                                        $badgeClass = match($role->name) {
                                                            'admin' => 'badge-danger',
                                                            'editor' => 'badge-warning',
                                                            'author' => 'badge-success',
                                                            default => 'badge-secondary'
                                                        };
                                                    @endphp
                                                    <span class="badge {{ $badgeClass }}">
                                                        {{ ucfirst($role->name) }}
                                                    </span>
                                                @endforeach
                                            </td>
                                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                                            <td class="text-right">
                                                @can('users.edit')
                                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                                       class="btn btn-sm btn-warning"
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan

                                                @can('users.delete')
                                                    @if($user->id !== auth()->id())
                                                        <button type="button"
                                                                class="btn btn-sm btn-danger"
                                                                onclick="deleteUser({{ $user->id }})"
                                                                title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>

                                                        <form id="delete-form-{{ $user->id }}"
                                                              action="{{ route('admin.users.destroy', $user->id) }}"
                                                              method="POST"
                                                              style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    @endif
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $users->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-4x text-muted mb-3"></i>
                            <h5>No Users Found</h5>
                            <p class="text-muted">Create your first user to get started</p>
                            @can('users.create')
                                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Add New User
                                </a>
                            @endcan
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
    <script>
        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
@endpush
