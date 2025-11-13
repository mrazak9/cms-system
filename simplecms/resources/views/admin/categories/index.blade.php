@extends('admin.layouts.app')

@section('title', 'Categories')
@section('page-title', 'Categories')

@section('breadcrumb')
    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
    <div class="breadcrumb-item active">Categories</div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-5">
            @if((isset($editCategory) && auth()->user()->can('categories.edit')) || (!isset($editCategory) && auth()->user()->can('categories.create')))
            <div class="card">
                <div class="card-header">
                    <h4>{{ isset($editCategory) ? 'Edit Category' : 'Add New Category' }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ isset($editCategory) ? route('admin.categories.update', $editCategory->id) : route('admin.categories.store') }}" method="POST">
                        @csrf
                        @if(isset($editCategory))
                            @method('PUT')
                        @endif

                        <div class="form-group">
                            <label for="name">Category Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $editCategory->name ?? '') }}"
                                   placeholder="Enter category name"
                                   required
                                   autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="slug">Slug <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-link"></i>
                                    </div>
                                </div>
                                <input type="text"
                                       class="form-control @error('slug') is-invalid @enderror"
                                       id="slug"
                                       name="slug"
                                       value="{{ old('slug', $editCategory->slug ?? '') }}"
                                       placeholder="category-slug"
                                       required>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">
                                URL-friendly version. Leave blank to auto-generate from name.
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="4"
                                      placeholder="Brief description of this category (optional)">{{ old('description', $editCategory->description ?? '') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-0">
                            @if(isset($editCategory))
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Category
                                </button>
                                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            @else
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Add Category
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h4>Help</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-2">
                        <i class="fas fa-info-circle text-primary"></i> Categories help organize your blog posts into different topics.
                    </p>
                    <p class="text-muted mb-2">
                        <i class="fas fa-lightbulb text-warning"></i> Use descriptive names that clearly represent the content.
                    </p>
                    <p class="text-muted mb-0">
                        <i class="fas fa-tags text-success"></i> You can assign categories to posts when creating or editing them.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h4>All Categories</h4>
                    <div class="card-header-action">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search categories..." id="searchCategory">
                            <div class="input-group-append">
                                <button class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Posts</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                    <tr>
                                        <td>{{ $category->id }}</td>
                                        <td>
                                            <strong>{{ $category->name }}</strong>
                                        </td>
                                        <td>
                                            <code>{{ $category->slug }}</code>
                                        </td>
                                        <td>
                                            @if($category->posts_count > 0)
                                                <span class="badge badge-primary">
                                                    <i class="fas fa-newspaper"></i> {{ $category->posts_count }}
                                                </span>
                                            @else
                                                <span class="text-muted">0</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($category->description)
                                                <small class="text-muted">{{ Str::limit($category->description, 40) }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                @can('categories.edit')
                                                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan
                                                @can('categories.delete')
                                                    <button type="button" class="btn btn-sm btn-danger" title="Delete"
                                                            onclick="deleteCategory({{ $category->id }})"
                                                            {{ $category->posts_count > 0 ? 'disabled' : '' }}>
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endcan
                                            </div>
                                            @can('categories.delete')
                                                <form id="delete-form-{{ $category->id }}"
                                                      action="{{ route('admin.categories.destroy', $category->id) }}"
                                                      method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="fas fa-tags fa-3x mb-3"></i>
                                            <p>No categories yet. Create your first category using the form.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($categories->hasPages())
                    <div class="card-footer text-right">
                        {{ $categories->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
<script>
    // Auto-generate slug from name
    $('#name').on('input', function() {
        let name = $(this).val();
        let slug = name.toLowerCase()
            .replace(/[^\w\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/--+/g, '-')
            .trim();
        $('#slug').val(slug);
    });

    // Delete category
    function deleteCategory(id) {
        if (confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }

    // Search functionality
    $('#searchCategory').on('keyup', function() {
        let value = $(this).val().toLowerCase();
        $('table tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
</script>
@endpush
