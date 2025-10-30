@extends('admin.layouts.app')

@section('title', 'Menus')
@section('page-title', 'Menu Management')

@section('breadcrumb')
    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
    <div class="breadcrumb-item active">Menus</div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>All Menus</h4>
                    <div class="card-header-action">
                        <a href="{{ route('admin.menus.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Create New Menu
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Location</th>
                                    <th>Items</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($menus ?? [] as $menu)
                                    <tr>
                                        <td>{{ $menu->id }}</td>
                                        <td>
                                            <strong>{{ $menu->name }}</strong>
                                            @if($menu->description)
                                                <br><small class="text-muted">{{ Str::limit($menu->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <code>{{ $menu->location ?? 'Not set' }}</code>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">
                                                {{ $menu->items_count ?? 0 }} items
                                            </span>
                                        </td>
                                        <td>
                                            @if($menu->is_active)
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check"></i> Active
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">
                                                    <i class="fas fa-times"></i> Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>{{ $menu->created_at->format('M d, Y') }}</div>
                                            <small class="text-muted">{{ $menu->created_at->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.menus.edit', $menu->id) }}"
                                                   class="btn btn-sm btn-primary"
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button"
                                                        class="btn btn-sm btn-danger"
                                                        title="Delete"
                                                        onclick="deleteMenu({{ $menu->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                            <form id="delete-form-{{ $menu->id }}"
                                                  action="{{ route('admin.menus.destroy', $menu->id) }}"
                                                  method="POST"
                                                  style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="fas fa-bars fa-3x mb-3"></i>
                                            <p>No menus found. <a href="{{ route('admin.menus.create') }}">Create your first menu</a></p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if(isset($menus) && $menus->hasPages())
                    <div class="card-footer text-right">
                        {{ $menus->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Menu Locations Info Card -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Available Menu Locations</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center p-3 border rounded">
                                <i class="fas fa-compass fa-2x text-primary mb-2"></i>
                                <h6>Primary Menu</h6>
                                <small class="text-muted">Main navigation menu</small>
                                <br>
                                <code>primary</code>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 border rounded">
                                <i class="fas fa-shoe-prints fa-2x text-success mb-2"></i>
                                <h6>Footer Menu</h6>
                                <small class="text-muted">Footer navigation menu</small>
                                <br>
                                <code>footer</code>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 border rounded">
                                <i class="fas fa-bars fa-2x text-warning mb-2"></i>
                                <h6>Sidebar Menu</h6>
                                <small class="text-muted">Sidebar navigation menu</small>
                                <br>
                                <code>sidebar</code>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 border rounded">
                                <i class="fas fa-ellipsis-h fa-2x text-info mb-2"></i>
                                <h6>Secondary Menu</h6>
                                <small class="text-muted">Additional menu</small>
                                <br>
                                <code>secondary</code>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i>
                            Menus can be assigned to different locations in your theme. Each theme may support different menu locations.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
<script>
    function deleteMenu(id) {
        if (confirm('Are you sure you want to delete this menu? All menu items will also be deleted. This action cannot be undone.')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush
