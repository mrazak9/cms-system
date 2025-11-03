@extends('admin.layouts.app')

@section('title', 'Create Role')
@section('page-title', 'Create New Role')

@section('breadcrumb')
    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
    <div class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Roles</a></div>
    <div class="breadcrumb-item active">Create</div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf

                <div class="card">
                    <div class="card-header">
                        <h4>Role Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Role Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   placeholder="e.g., manager, customer"
                                   required
                                   autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Use lowercase letters only. Example: manager, customer, moderator
                            </small>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4>Assign Permissions</h4>
                        <div class="card-header-action">
                            <button type="button" class="btn btn-sm btn-primary" onclick="selectAll()">
                                Select All
                            </button>
                            <button type="button" class="btn btn-sm btn-secondary" onclick="deselectAll()">
                                Deselect All
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($permissions->count() > 0)
                            <div class="row">
                                @foreach($permissions as $group => $groupPermissions)
                                    <div class="col-md-6 mb-4">
                                        <div class="border rounded p-3 h-100">
                                            <h6 class="mb-3 text-primary">
                                                <i class="fas fa-folder"></i> {{ ucfirst($group) }}
                                            </h6>
                                            @foreach($groupPermissions as $permission)
                                                <div class="custom-control custom-checkbox mb-2">
                                                    <input type="checkbox"
                                                           class="custom-control-input permission-checkbox"
                                                           id="permission-{{ $permission->id }}"
                                                           name="permissions[]"
                                                           value="{{ $permission->name }}"
                                                           {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="permission-{{ $permission->id }}">
                                                        {{ ucwords(str_replace(['.', '-'], ' ', $permission->name)) }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-warning">
                                No permissions available. Please seed permissions first.
                            </div>
                        @endif

                        @error('permissions')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Role
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('custom-scripts')
    <script>
        function selectAll() {
            document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
                checkbox.checked = true;
            });
        }

        function deselectAll() {
            document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });
        }
    </script>
@endpush
