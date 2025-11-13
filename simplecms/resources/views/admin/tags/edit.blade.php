@extends('admin.layouts.app')

@section('title', 'Edit Tag')
@section('page-title', 'Edit Tag: ' . $tag->name)

@section('breadcrumb')
    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
    <div class="breadcrumb-item"><a href="{{ route('admin.tags.index') }}">Tags</a></div>
    <div class="breadcrumb-item active">Edit</div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4>Tag Information</h4>
                    <div class="card-header-action">
                        <span class="badge badge-info">{{ $tag->posts()->count() }} posts</span>
                    </div>
                </div>
                <form action="{{ route('admin.tags.update', $tag->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        {{-- Name --}}
                        <div class="form-group">
                            <label for="name">Tag Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $tag->name) }}"
                                   required
                                   autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Slug --}}
                        <div class="form-group">
                            <label for="slug">Slug <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="slug"
                                   id="slug"
                                   class="form-control @error('slug') is-invalid @enderror"
                                   value="{{ old('slug', $tag->slug) }}"
                                   required>
                            <small class="form-text text-muted">URL-friendly version of the name.</small>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description"
                                      id="description"
                                      rows="3"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Brief description of this tag (optional)">{{ old('description', $tag->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Info --}}
                        <div class="alert alert-info">
                            <p class="mb-0"><strong>Created:</strong> {{ $tag->created_at->format('M d, Y H:i') }}</p>
                            <p class="mb-0"><strong>Last Updated:</strong> {{ $tag->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('admin.tags.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Tag</button>
                    </div>
                </form>
            </div>

            {{-- Delete Card --}}
            @can('tags.delete')
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h4 class="text-white">Danger Zone</h4>
                </div>
                <div class="card-body">
                    <p class="mb-3">
                        <strong>Delete this tag:</strong> This action cannot be undone.
                        @if($tag->posts()->count() > 0)
                            This tag is currently used by {{ $tag->posts()->count() }} post(s). Deleting will remove the tag from all posts.
                        @endif
                    </p>
                    <form action="{{ route('admin.tags.destroy', $tag->id) }}" method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this tag? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Delete Tag
                        </button>
                    </form>
                </div>
            </div>
            @endcan
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Auto-generate slug from name
    document.getElementById('name').addEventListener('input', function() {
        const name = this.value;
        const slug = name.toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim();

        // Only auto-fill if slug hasn't been manually edited
        const slugField = document.getElementById('slug');
        if (slugField.getAttribute('data-auto') !== 'false') {
            slugField.value = slug;
        }
    });

    // Mark as manually edited if user types in slug field
    document.getElementById('slug').addEventListener('input', function() {
        this.setAttribute('data-auto', 'false');
    });
</script>
@endpush
