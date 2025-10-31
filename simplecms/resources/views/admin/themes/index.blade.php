@extends('admin.layouts.app')

@section('title', 'Themes')
@section('page-title', 'Themes')

@section('breadcrumb')
    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
    <div class="breadcrumb-item active">Themes</div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Available Themes</h4>
                    <div class="card-header-action">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#uploadThemeModal">
                            <i class="fas fa-upload"></i> Upload New Theme
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if (isset($themes) && count($themes) > 0)
                        <div class="row">
                            @foreach ($themes as $theme)
                                <div class="col-lg-4 col-md-6 col-12 mb-4">
                                    <div class="card h-100">
                                        @if ($theme->thumbnail)
                                            <img src="{{ asset('storage/' . $theme->thumbnail) }}" class="card-img-top"
                                                alt="{{ $theme->name }}" style="height: 200px; object-fit: cover;">
                                        @else
                                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                                style="height: 200px;">
                                                <i class="fas fa-paint-brush fa-4x text-muted"></i>
                                            </div>
                                        @endif
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title">{{ $theme->name }}</h5>
                                            <p class="card-text text-muted flex-grow-1">
                                                {{ $theme->description ?? 'No description available' }}
                                            </p>
                                            <div class="mt-auto">
                                                @if ($theme->is_active)
                                                    <span class="badge badge-success badge-lg mb-2">
                                                        <i class="fas fa-check-circle"></i> Active Theme
                                                    </span>
                                                @endif
                                                <div class="text-small text-muted mb-2">
                                                    @if ($theme->version)
                                                        <strong>Version:</strong> {{ $theme->version }}<br>
                                                    @endif
                                                    @if ($theme->author)
                                                        <strong>Author:</strong> {{ $theme->author }}
                                                    @endif
                                                </div>
                                                <div class="btn-group btn-block">
                                                    @if (!$theme->is_active)
                                                        <form action="{{ route('admin.themes.activate', $theme->id) }}"
                                                            method="POST" class="w-100">
                                                            @csrf
                                                            <button type="submit" class="btn btn-primary btn-block"
                                                                onclick="return confirm('Are you sure you want to activate this theme?')">
                                                                <i class="fas fa-check"></i> Activate
                                                            </button>
                                                        </form>
                                                    @else
                                                        <button class="btn btn-success btn-block" disabled>
                                                            <i class="fas fa-check-circle"></i> Currently Active
                                                        </button>
                                                    @endif
                                                </div>
                                                <a href="{{ route('admin.themes.settings.edit', $theme->id) }}"
                                                    class="btn btn-info btn-block mt-2">
                                                    <i class="fas fa-cog"></i> Edit Content
                                                </a>
                                                @if (!$theme->is_active)
                                                    <button type="button" class="btn btn-danger btn-block mt-2"
                                                        onclick="deleteTheme({{ $theme->id }})">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                    <form id="delete-form-{{ $theme->id }}"
                                                        action="{{ route('admin.themes.destroy', $theme->id) }}"
                                                        method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-paint-brush fa-4x mb-3"></i>
                            <h5>No Themes Available</h5>
                            <p>Upload your first theme to get started</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

<!-- Upload Theme Modal -->
<div class="modal fade" id="uploadThemeModal" tabindex="-1" role="dialog" aria-labelledby="uploadThemeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.themes.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadThemeModalLabel">Upload New Theme</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="theme_name">Theme Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="theme_name"
                            name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="theme_description">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="theme_description" name="description"
                            rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="theme_thumbnail">Thumbnail</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input @error('thumbnail') is-invalid @enderror"
                                id="theme_thumbnail" name="thumbnail" accept="image/*">
                            <label class="custom-file-label" for="theme_thumbnail">Choose file</label>
                            @error('thumbnail')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="form-text text-muted">Recommended size: 800x600px</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Upload Theme
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('custom-scripts')
    <script>
        function deleteTheme(id) {
            if (confirm('Are you sure you want to delete this theme? This action cannot be undone.')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }

        // Update file input label with filename
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });

        // Auto-show modal if there are validation errors
        @if ($errors->any())
            $('#uploadThemeModal').modal('show');
        @endif
    </script>
@endpush
