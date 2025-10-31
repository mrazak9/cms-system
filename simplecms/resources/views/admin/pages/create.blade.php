@extends('admin.layouts.app')

@section('title', 'Create Page')
@section('page-title', 'Create New Page')

@section('breadcrumb')
    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
    <div class="breadcrumb-item"><a href="{{ route('admin.pages.index') }}">Pages</a></div>
    <div class="breadcrumb-item active">Create</div>
@endsection

@section('content')
    <form action="{{ route('admin.pages.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Page Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="title">Page Title <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('title') is-invalid @enderror"
                                   id="title"
                                   name="title"
                                   value="{{ old('title') }}"
                                   placeholder="Enter page title"
                                   required>
                            @error('title')
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
                                       value="{{ old('slug') }}"
                                       placeholder="page-slug"
                                       required>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">
                                URL-friendly version of the title. Leave blank to auto-generate from title.
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="meta_description">Meta Description</label>
                            <textarea class="form-control @error('meta_description') is-invalid @enderror"
                                      id="meta_description"
                                      name="meta_description"
                                      rows="3"
                                      placeholder="Brief description for SEO (recommended 150-160 characters)">{{ old('meta_description') }}</textarea>
                            @error('meta_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <span id="meta-description-count">0</span> characters
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="meta_keywords">Meta Keywords</label>
                            <input type="text"
                                   class="form-control @error('meta_keywords') is-invalid @enderror"
                                   id="meta_keywords"
                                   name="meta_keywords"
                                   value="{{ old('meta_keywords') }}"
                                   placeholder="keyword1, keyword2, keyword3">
                            @error('meta_keywords')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Separate keywords with commas
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Publishing Options</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="theme_id">
                                <i class="fas fa-paint-brush"></i> Theme
                            </label>
                            <select class="form-control @error('theme_id') is-invalid @enderror"
                                    id="theme_id"
                                    name="theme_id">
                                @php
                                    $activeTheme = \App\Models\Theme::where('is_active', true)->first();
                                @endphp
                                <option value="">
                                    Use Active Theme
                                    @if($activeTheme)
                                        ({{ $activeTheme->name }})
                                    @endif
                                </option>
                                @foreach($themes ?? [] as $theme)
                                    <option value="{{ $theme->id }}" {{ old('theme_id') == $theme->id ? 'selected' : '' }}>
                                        {{ $theme->name }}
                                        @if($theme->is_active)
                                            ★ Active
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('theme_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Select a specific theme for this page, or use the active theme.
                            </small>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox"
                                       class="custom-control-input"
                                       id="is_published"
                                       name="is_published"
                                       value="1"
                                       {{ old('is_published', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_published">
                                    <strong>Published</strong>
                                    <br>
                                    <small class="text-muted">Make this page publicly visible</small>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox"
                                       class="custom-control-input"
                                       id="is_homepage"
                                       name="is_homepage"
                                       value="1"
                                       {{ old('is_homepage') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_homepage">
                                    <strong>Set as Homepage</strong>
                                    <br>
                                    <small class="text-muted">Display this page as the site homepage</small>
                                </label>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary btn-lg btn-block">
                                <i class="fas fa-save"></i> Create Page
                            </button>
                            <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary btn-lg btn-block">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4>Help</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-2">
                            <i class="fas fa-info-circle text-primary"></i> After creating the page, you can add sections with different templates in the edit view.
                        </p>
                        <p class="text-muted mb-0">
                            <i class="fas fa-lightbulb text-warning"></i> Use descriptive meta descriptions to improve SEO.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('custom-scripts')
<script>
    // Auto-generate slug from title
    $('#title').on('input', function() {
        let title = $(this).val();
        let slug = title.toLowerCase()
            .replace(/[^\w\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/--+/g, '-')
            .trim();
        $('#slug').val(slug);
    });

    // Count meta description characters
    $('#meta_description').on('input', function() {
        let count = $(this).val().length;
        $('#meta-description-count').text(count);

        if (count > 160) {
            $('#meta-description-count').addClass('text-danger');
        } else if (count > 150) {
            $('#meta-description-count').addClass('text-warning').removeClass('text-danger');
        } else {
            $('#meta-description-count').removeClass('text-warning text-danger');
        }
    });

    // Trigger count on page load
    $('#meta_description').trigger('input');
</script>
@endpush
