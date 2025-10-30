@extends('admin.layouts.app')

@section('title', 'Edit Page')
@section('page-title', 'Edit Page')

@section('breadcrumb')
    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
    <div class="breadcrumb-item"><a href="{{ route('admin.pages.index') }}">Pages</a></div>
    <div class="breadcrumb-item active">Edit: {{ $page->title }}</div>
@endsection

@section('content')
    <form action="{{ route('admin.pages.update', $page->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-lg-8">
                <!-- Page Information -->
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
                                   value="{{ old('title', $page->title) }}"
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
                                       value="{{ old('slug', $page->slug) }}"
                                       placeholder="page-slug"
                                       required>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            @if($page->is_published)
                                <small class="form-text text-muted">
                                    <i class="fas fa-exclamation-triangle text-warning"></i>
                                    Current URL: <a href="{{ url($page->slug) }}" target="_blank">{{ url($page->slug) }}</a>
                                </small>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="meta_description">Meta Description</label>
                            <textarea class="form-control @error('meta_description') is-invalid @enderror"
                                      id="meta_description"
                                      name="meta_description"
                                      rows="3"
                                      placeholder="Brief description for SEO (recommended 150-160 characters)">{{ old('meta_description', $page->meta_description) }}</textarea>
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
                                   value="{{ old('meta_keywords', $page->meta_keywords) }}"
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

                <!-- Page Sections -->
                <div class="card">
                    <div class="card-header">
                        <h4>Page Sections</h4>
                        <div class="card-header-action">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addSectionModal">
                                <i class="fas fa-plus"></i> Add Section
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="sections-container">
                            @forelse($page->sections ?? [] as $index => $section)
                                <div class="section-item card mb-3" data-section-id="{{ $section->id }}">
                                    <div class="card-header bg-light">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas fa-grip-vertical text-muted mr-2"></i>
                                                <strong>{{ $section->template->name ?? 'Section ' . ($index + 1) }}</strong>
                                                <span class="badge badge-info ml-2">{{ $section->template->slug ?? 'N/A' }}</span>
                                            </div>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-info" onclick="editSection({{ $section->id }})">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-primary" onclick="moveSection({{ $section->id }}, 'up')" {{ $index == 0 ? 'disabled' : '' }}>
                                                    <i class="fas fa-arrow-up"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-primary" onclick="moveSection({{ $section->id }}, 'down')" {{ $index == count($page->sections) - 1 ? 'disabled' : '' }}>
                                                    <i class="fas fa-arrow-down"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteSection({{ $section->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <pre class="mb-0"><code>{{ json_encode($section->content, JSON_PRETTY_PRINT) }}</code></pre>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted py-4" id="no-sections-message">
                                    <i class="fas fa-layer-group fa-3x mb-3"></i>
                                    <p>No sections added yet. Click "Add Section" to start building your page.</p>
                                </div>
                            @endforelse
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
                            <label for="theme_id">Theme</label>
                            <select class="form-control @error('theme_id') is-invalid @enderror"
                                    id="theme_id"
                                    name="theme_id">
                                <option value="">Default Theme</option>
                                @foreach($themes ?? [] as $theme)
                                    <option value="{{ $theme->id }}"
                                            {{ old('theme_id', $page->theme_id) == $theme->id ? 'selected' : '' }}>
                                        {{ $theme->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('theme_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox"
                                       class="custom-control-input"
                                       id="is_published"
                                       name="is_published"
                                       value="1"
                                       {{ old('is_published', $page->is_published) ? 'checked' : '' }}>
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
                                       {{ old('is_homepage', $page->is_homepage) ? 'checked' : '' }}>
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
                                <i class="fas fa-save"></i> Update Page
                            </button>
                            @if($page->is_published)
                                <a href="{{ url($page->slug) }}" target="_blank" class="btn btn-info btn-lg btn-block">
                                    <i class="fas fa-eye"></i> Preview Page
                                </a>
                            @endif
                            <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary btn-lg btn-block">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4>Page Info</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Created:</strong></td>
                                <td>{{ $page->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Updated:</strong></td>
                                <td>{{ $page->updated_at->format('M d, Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Sections:</strong></td>
                                <td>{{ count($page->sections ?? []) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Add Section Modal -->
    <div class="modal fade" id="addSectionModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Section</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addSectionForm" action="{{ route('admin.pages.sections.store', $page->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="section_template_id">Section Template <span class="text-danger">*</span></label>
                            <select class="form-control" id="section_template_id" name="template_id" required>
                                <option value="">Select a template...</option>
                                @foreach($sectionTemplates ?? [] as $template)
                                    <option value="{{ $template->id }}">{{ $template->name }} - {{ $template->slug }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="section_content">Content (JSON) <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="section_content" name="content" rows="10" required>{}</textarea>
                            <small class="form-text text-muted">
                                Enter section content as JSON. Example: {"title": "Welcome", "text": "Hello world"}
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Section</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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

    // Section management functions
    function editSection(sectionId) {
        alert('Edit section functionality - integrate with your section edit endpoint');
    }

    function moveSection(sectionId, direction) {
        // TODO: Implement AJAX call to reorder sections
        alert('Move section ' + direction + ' - section ID: ' + sectionId);
    }

    function deleteSection(sectionId) {
        if (confirm('Are you sure you want to delete this section?')) {
            // TODO: Implement AJAX call to delete section
            alert('Delete section - section ID: ' + sectionId);
        }
    }

    // Make sections sortable (requires jQuery UI - add if needed)
    // $('#sections-container').sortable({
    //     handle: '.fa-grip-vertical',
    //     update: function(event, ui) {
    //         // Update section order
    //     }
    // });
</script>
@endpush
