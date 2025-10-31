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
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                                name="title" value="{{ old('title', $page->title) }}" placeholder="Enter page title"
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
                                <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                    id="slug" name="slug" value="{{ old('slug', $page->slug) }}"
                                    placeholder="page-slug" required>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            @if ($page->is_published)
                                <small class="form-text text-muted">
                                    <i class="fas fa-exclamation-triangle text-warning"></i>
                                    Current URL: <a href="{{ url($page->slug) }}" target="_blank">{{ url($page->slug) }}</a>
                                </small>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="meta_description">Meta Description</label>
                            <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description"
                                name="meta_description" rows="3" placeholder="Brief description for SEO (recommended 150-160 characters)">{{ old('meta_description', $page->meta_description) }}</textarea>
                            @error('meta_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <span id="meta-description-count">0</span> characters
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="meta_keywords">Meta Keywords</label>
                            <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror"
                                id="meta_keywords" name="meta_keywords"
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
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#addSectionModal">
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
                                                <strong>{{ $section->sectionTemplate->name ?? 'Section ' . ($index + 1) }}</strong>
                                                <span
                                                    class="badge badge-info ml-2">{{ $section->sectionTemplate->slug ?? 'N/A' }}</span>
                                            </div>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-info"
                                                    onclick="editSection({{ $section->id }})">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-primary"
                                                    onclick="moveSection({{ $section->id }}, 'up')"
                                                    {{ $index == 0 ? 'disabled' : '' }}>
                                                    <i class="fas fa-arrow-up"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-primary"
                                                    onclick="moveSection({{ $section->id }}, 'down')"
                                                    {{ $index == count($page->sections) - 1 ? 'disabled' : '' }}>
                                                    <i class="fas fa-arrow-down"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    onclick="deleteSection({{ $section->id }})">
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
                            <label for="theme_id">
                                <i class="fas fa-paint-brush"></i> Theme
                            </label>
                            <select class="form-control @error('theme_id') is-invalid @enderror" id="theme_id"
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
                                @foreach ($themes ?? [] as $theme)
                                    <option value="{{ $theme->id }}"
                                        {{ old('theme_id', $page->theme_id) == $theme->id ? 'selected' : '' }}>
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
                                <input type="checkbox" class="custom-control-input" id="is_published"
                                    name="is_published" value="1"
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
                                <input type="checkbox" class="custom-control-input" id="is_homepage" name="is_homepage"
                                    value="1" {{ old('is_homepage', $page->is_homepage) ? 'checked' : '' }}>
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
                            @if ($page->is_published)
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
@endsection

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
                        <label>Section Template <span class="text-danger">*</span></label>
                        <div class="template-selector-tabs mb-3">
                            <ul class="nav nav-pills" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#template-list-view">
                                        <i class="fas fa-list"></i> List
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#template-grid-view">
                                        <i class="fas fa-th"></i> Grid
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-content">
                            <!-- List View -->
                            <div class="tab-pane fade show active" id="template-list-view">
                                <select class="form-control" id="section_template_id" name="template_id" required>
                                    <option value="">Select a template...</option>
                                    @foreach ($sectionTemplates ?? [] as $template)
                                        <option value="{{ $template->id }}"
                                                data-fields="{{ json_encode($template->fields ?? []) }}"
                                                data-defaults="{{ json_encode($template->default_fields ?? []) }}"
                                                data-category="{{ $template->category ?? 'general' }}">
                                            {{ $template->name }} <span class="text-muted">({{ $template->category ?? 'general' }})</span>
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Choose a section template from the dropdown</small>
                            </div>

                            <!-- Grid View -->
                            <div class="tab-pane fade" id="template-grid-view">
                                <div class="row template-grid">
                                    @foreach ($sectionTemplates ?? [] as $template)
                                        <div class="col-md-4 col-sm-6 mb-3">
                                            <div class="template-card" data-template-id="{{ $template->id }}"
                                                 data-fields="{{ json_encode($template->fields ?? []) }}"
                                                 data-defaults="{{ json_encode($template->default_fields ?? []) }}"
                                                 onclick="selectTemplateCard(this)">
                                                <div class="template-card-preview">
                                                    @if($template->preview_image)
                                                        <img src="{{ asset($template->preview_image) }}" alt="{{ $template->name }}">
                                                    @else
                                                        <div class="template-card-icon">
                                                            <i class="fas fa-{{ $template->category === 'hero' ? 'image' : ($template->category === 'features' ? 'star' : ($template->category === 'about' ? 'info-circle' : 'th')) }} fa-3x"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="template-card-body">
                                                    <h6 class="mb-1">{{ $template->name }}</h6>
                                                    <small class="text-muted">
                                                        <span class="badge badge-info">{{ $template->category ?? 'general' }}</span>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <small class="form-text text-muted">Click on a card to select a template</small>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div id="dynamic-fields-container">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Please select a template first to see available fields
                        </div>
                    </div>

                    <div class="form-group" id="json-view-container" style="display: none;">
                        <label>
                            <input type="checkbox" id="show-json-view"> Advanced: Show JSON View
                        </label>
                        <textarea class="form-control" id="section_content_json" rows="10" style="display: none;"></textarea>
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

<!-- Edit Section Modal -->
<div class="modal fade" id="editSectionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Section</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editSectionForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_section_id" name="section_id">
                <input type="hidden" id="edit_section_template_id" name="template_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Section Template</label>
                        <input type="text" class="form-control" id="edit_section_template" readonly>
                    </div>

                    <hr>

                    <div id="edit-dynamic-fields-container">
                        <div class="alert alert-info">
                            <i class="fas fa-spinner fa-spin"></i> Loading section data...
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="edit_is_visible" name="is_visible" value="1">
                            <label class="custom-control-label" for="edit_is_visible">
                                Visible
                            </label>
                        </div>
                    </div>

                    <div class="form-group" id="edit-json-view-container" style="display: none;">
                        <label>
                            <input type="checkbox" id="edit-show-json-view"> Advanced: Show JSON View
                        </label>
                        <textarea class="form-control" id="edit_section_content_json" rows="10" style="display: none;"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Section</button>
                </div>
            </form>
        </div>
    </div>
</div>

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

        // Dynamic Form Builder Function
        function buildDynamicForm(fields, defaults = {}, containerId = 'dynamic-fields-container') {
            const container = $(`#${containerId}`);
            container.empty();

            if (!fields || fields.length === 0) {
                container.html('<div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> No fields defined for this template. Please add fields definition.</div>');
                return;
            }

            fields.forEach(field => {
                const fieldId = `field_${field.name}`;
                const value = defaults[field.name] || field.default || '';
                const required = field.required ? 'required' : '';
                const requiredMark = field.required ? '<span class="text-danger">*</span>' : '';

                let fieldHtml = `<div class="form-group">
                    <label for="${fieldId}">${field.label} ${requiredMark}</label>`;

                switch(field.type) {
                    case 'text':
                    case 'url':
                    case 'email':
                    case 'number':
                        fieldHtml += `<input type="${field.type}" class="form-control dynamic-field"
                            id="${fieldId}" name="${field.name}" value="${value}" ${required}
                            placeholder="${field.placeholder || ''}">`;
                        break;

                    case 'textarea':
                        fieldHtml += `<textarea class="form-control dynamic-field" id="${fieldId}"
                            name="${field.name}" rows="${field.rows || 3}" ${required}
                            placeholder="${field.placeholder || ''}">${value}</textarea>`;
                        break;

                    case 'select':
                        fieldHtml += `<select class="form-control dynamic-field" id="${fieldId}"
                            name="${field.name}" ${required}>`;
                        if (!field.required) {
                            fieldHtml += '<option value="">-- Select --</option>';
                        }
                        if (field.options) {
                            field.options.forEach(opt => {
                                const selected = value === opt.value ? 'selected' : '';
                                fieldHtml += `<option value="${opt.value}" ${selected}>${opt.label}</option>`;
                            });
                        }
                        fieldHtml += '</select>';
                        break;

                    case 'checkbox':
                        const checked = value ? 'checked' : '';
                        fieldHtml += `<div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input dynamic-field"
                                id="${fieldId}" name="${field.name}" value="1" ${checked}>
                            <label class="custom-control-label" for="${fieldId}">${field.description || ''}</label>
                        </div>`;
                        break;

                    case 'image':
                    case 'file':
                        fieldHtml += `<input type="text" class="form-control dynamic-field"
                            id="${fieldId}" name="${field.name}" value="${value}" ${required}
                            placeholder="${field.placeholder || 'Enter file URL or path'}">
                        <small class="form-text text-muted">Enter the file path (e.g., storage/images/hero.jpg) or full URL</small>`;
                        break;

                    case 'color':
                        fieldHtml += `<div class="input-group">
                            <input type="text" class="form-control dynamic-field"
                                id="${fieldId}" name="${field.name}" value="${value}" ${required}
                                placeholder="#000000">
                            <div class="input-group-append">
                                <input type="color" class="form-control" style="width: 50px;" value="${value || '#000000'}"
                                    onchange="document.getElementById('${fieldId}').value = this.value">
                            </div>
                        </div>`;
                        break;

                    case 'repeater':
                    case 'array':
                        // Repeater field for array of items (e.g., features, team members)
                        fieldHtml += `<div class="repeater-container" id="${fieldId}_container">
                            <div class="repeater-items" id="${fieldId}_items"></div>
                            <button type="button" class="btn btn-sm btn-success mt-2" onclick="addRepeaterItem('${fieldId}', ${JSON.stringify(field.fields || []).replace(/"/g, '&quot;')})">
                                <i class="fas fa-plus"></i> Add ${field.item_label || 'Item'}
                            </button>
                            <textarea class="form-control dynamic-field" id="${fieldId}" name="${field.name}" style="display:none;"></textarea>
                        </div>`;
                        break;

                    case 'json':
                        // Raw JSON editor for complex data
                        fieldHtml += `<textarea class="form-control dynamic-field font-monospace" id="${fieldId}"
                            name="${field.name}" rows="${field.rows || 10}" ${required}
                            placeholder='${field.placeholder || '{"key": "value"}'}'>${typeof value === 'object' ? JSON.stringify(value, null, 2) : value}</textarea>
                        <small class="form-text text-muted">Enter valid JSON format</small>`;
                        break;
                }

                if (field.help) {
                    fieldHtml += `<small class="form-text text-muted">${field.help}</small>`;
                }
                fieldHtml += '</div>';

                container.append(fieldHtml);
            });

            $('#json-view-container').show();

            // Initialize repeater fields with existing data
            fields.forEach(field => {
                if ((field.type === 'repeater' || field.type === 'array') && defaults[field.name]) {
                    const fieldId = `field_${field.name}`;
                    const items = Array.isArray(defaults[field.name]) ? defaults[field.name] : [];
                    items.forEach((itemData, index) => {
                        addRepeaterItem(fieldId, field.fields || [], itemData, index);
                    });
                }
            });
        }

        // Add Repeater Item Function
        window.addRepeaterItem = function(fieldId, fields, data = {}, index = null) {
            const container = $(`#${fieldId}_items`);
            const itemIndex = index !== null ? index : container.children().length;
            const itemId = `${fieldId}_item_${itemIndex}_${Date.now()}`;

            let itemHtml = `<div class="card mb-2 repeater-item" data-item-id="${itemId}">
                <div class="card-header bg-light py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted"><i class="fas fa-grip-vertical"></i> Item ${itemIndex + 1}</small>
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeRepeaterItem('${itemId}', '${fieldId}')">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body py-2">`;

            // Build fields for each repeater item
            fields.forEach(subField => {
                const subFieldId = `${itemId}_${subField.name}`;
                const value = data[subField.name] || subField.default || '';
                const required = subField.required ? 'required' : '';

                itemHtml += `<div class="form-group mb-2">
                    <label for="${subFieldId}" class="mb-1"><small>${subField.label}</small></label>`;

                switch(subField.type) {
                    case 'text':
                    case 'url':
                    case 'email':
                        itemHtml += `<input type="${subField.type}" class="form-control form-control-sm repeater-field"
                            id="${subFieldId}" data-field="${subField.name}" value="${value}" ${required}
                            placeholder="${subField.placeholder || ''}">`;
                        break;

                    case 'textarea':
                        itemHtml += `<textarea class="form-control form-control-sm repeater-field" id="${subFieldId}"
                            data-field="${subField.name}" rows="${subField.rows || 2}" ${required}
                            placeholder="${subField.placeholder || ''}">${value}</textarea>`;
                        break;

                    case 'select':
                        itemHtml += `<select class="form-control form-control-sm repeater-field" id="${subFieldId}"
                            data-field="${subField.name}" ${required}>`;
                        if (!subField.required) {
                            itemHtml += '<option value="">-- Select --</option>';
                        }
                        if (subField.options) {
                            subField.options.forEach(opt => {
                                const selected = value === opt.value ? 'selected' : '';
                                itemHtml += `<option value="${opt.value}" ${selected}>${opt.label}</option>`;
                            });
                        }
                        itemHtml += '</select>';
                        break;

                    case 'checkbox':
                        const checked = value ? 'checked' : '';
                        itemHtml += `<div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input repeater-field"
                                id="${subFieldId}" data-field="${subField.name}" value="1" ${checked}>
                            <label class="custom-control-label" for="${subFieldId}"><small>${subField.description || ''}</small></label>
                        </div>`;
                        break;

                    case 'image':
                    case 'file':
                        itemHtml += `<input type="text" class="form-control form-control-sm repeater-field"
                            id="${subFieldId}" data-field="${subField.name}" value="${value}" ${required}
                            placeholder="${subField.placeholder || 'Enter file URL'}">`;
                        break;
                }

                itemHtml += '</div>';
            });

            itemHtml += `</div></div>`;

            container.append(itemHtml);

            // Update hidden field
            updateRepeaterData(fieldId);
        };

        // Remove Repeater Item Function
        window.removeRepeaterItem = function(itemId, fieldId) {
            $(`[data-item-id="${itemId}"]`).remove();
            updateRepeaterData(fieldId);

            // Re-index item numbers
            $(`#${fieldId}_items .repeater-item`).each(function(index) {
                $(this).find('.card-header small').html(`<i class="fas fa-grip-vertical"></i> Item ${index + 1}`);
            });
        };

        // Update Repeater Hidden Field
        function updateRepeaterData(fieldId) {
            const items = [];
            $(`#${fieldId}_items .repeater-item`).each(function() {
                const itemData = {};
                $(this).find('.repeater-field').each(function() {
                    const fieldName = $(this).data('field');
                    let value;

                    if ($(this).attr('type') === 'checkbox') {
                        value = $(this).is(':checked');
                    } else {
                        value = $(this).val();
                    }

                    itemData[fieldName] = value;
                });
                items.push(itemData);
            });

            $(`#${fieldId}`).val(JSON.stringify(items));
        }

        // Listen for changes in repeater fields
        $(document).on('input change', '.repeater-field', function() {
            const fieldId = $(this).closest('.repeater-container').find('textarea.dynamic-field').attr('id');
            updateRepeaterData(fieldId);
        });

        // Collect Form Data as JSON
        function collectFormData(containerId = null) {
            const data = {};
            const selector = containerId ? `#${containerId} .dynamic-field` : '.dynamic-field';

            $(selector).each(function() {
                const name = $(this).attr('name');
                let value;

                if ($(this).attr('type') === 'checkbox') {
                    value = $(this).is(':checked');
                } else if ($(this).attr('type') === 'number') {
                    value = parseFloat($(this).val()) || 0;
                } else {
                    value = $(this).val();
                }

                // Handle JSON fields - parse if it's a JSON string
                if ($(this).hasClass('font-monospace') || $(this).parent().hasClass('repeater-container')) {
                    try {
                        // Try to parse as JSON
                        const parsed = JSON.parse(value);
                        value = parsed;
                    } catch (e) {
                        // If parsing fails, keep as string (unless it's empty)
                        if (value === '' || value === '[]' || value === '{}') {
                            value = $(this).parent().hasClass('repeater-container') ? [] : value;
                        }
                    }
                }

                data[name] = value;
            });
            return data;
        }

        // Template Card Selection Handler
        window.selectTemplateCard = function(card) {
            // Remove active class from all cards
            $('.template-card').removeClass('active');
            // Add active class to selected card
            $(card).addClass('active');

            // Get template data
            const templateId = $(card).data('template-id');
            const fields = $(card).data('fields');
            const defaults = $(card).data('defaults');

            // Update the select dropdown
            $('#section_template_id').val(templateId).trigger('change');

            // Build dynamic form
            if (fields && fields.length > 0) {
                buildDynamicForm(fields, defaults, 'dynamic-fields-container');
            } else {
                $('#dynamic-fields-container').html('<div class="alert alert-info"><i class="fas fa-info-circle"></i> No custom fields defined. Using default JSON input.</div>');
                $('#json-view-container').hide();
            }
        };

        // Template Selection Handler for Add Modal (Dropdown)
        $('#section_template_id').on('change', function() {
            const selected = $(this).find(':selected');
            const fields = selected.data('fields');
            const defaults = selected.data('defaults');
            const templateId = $(this).val();

            // Sync grid view selection
            $('.template-card').removeClass('active');
            $(`.template-card[data-template-id="${templateId}"]`).addClass('active');

            if (fields && fields.length > 0) {
                buildDynamicForm(fields, defaults, 'dynamic-fields-container');
            } else {
                $('#dynamic-fields-container').html('<div class="alert alert-info"><i class="fas fa-info-circle"></i> No custom fields defined. Using default JSON input.</div>');
                $('#json-view-container').hide();
            }
        });

        // JSON View Toggle
        $('#show-json-view').on('change', function() {
            if ($(this).is(':checked')) {
                const jsonData = collectFormData();
                $('#section_content_json').val(JSON.stringify(jsonData, null, 2)).show();
            } else {
                $('#section_content_json').hide();
            }
        });

        // Update JSON view when fields change
        $(document).on('input change', '.dynamic-field', function() {
            if ($('#show-json-view').is(':checked')) {
                const jsonData = collectFormData();
                $('#section_content_json').val(JSON.stringify(jsonData, null, 2));
            }
        });

        // Handle Add Section Form submission
        $('#addSectionForm').on('submit', function(e) {
            e.preventDefault();

            const form = $(this);
            const templateId = $('#section_template_id').val();

            // Collect data from dynamic form
            const contentObj = collectFormData();

            // Submit via AJAX
            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: {
                    _token: $('input[name="_token"]', form).val(),
                    template_id: templateId,
                    content: contentObj,
                    is_visible: 1
                },
                success: function(response) {
                    if (response.success) {
                        // Reload page to show new section
                        location.reload();
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'Failed to add section.';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMsg = Object.values(xhr.responseJSON.errors).flat().join('\n');
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    alert(errorMsg);
                }
            });
        });

        // Edit Section Function
        window.editSection = function(sectionId) {
            // Get section data via AJAX
            $.ajax({
                url: '{{ route("admin.pages.sections.update", ["page" => $page->id, "section" => "__SECTION_ID__"]) }}'.replace('__SECTION_ID__', sectionId),
                method: 'GET',
                success: function(response) {
                    // Populate edit modal
                    $('#edit_section_id').val(sectionId);
                    $('#edit_section_template_id').val(response.section.section_template.id);
                    $('#edit_section_template').val(response.section.section_template.name + ' - ' + response.section.section_template.slug);
                    $('#edit_is_visible').prop('checked', response.section.is_visible);

                    // Get template fields and build dynamic form
                    const templateId = response.section.section_template.id;
                    const $templateOption = $(`#section_template_id option[value="${templateId}"]`);
                    const fields = $templateOption.data('fields');

                    if (fields && fields.length > 0) {
                        // Build dynamic form with existing content
                        buildDynamicForm(fields, response.section.content, 'edit-dynamic-fields-container');
                        $('#edit-json-view-container').show();
                    } else {
                        // No fields defined, show warning
                        $('#edit-dynamic-fields-container').html(
                            '<div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> No fields defined for this template.</div>' +
                            '<div class="form-group">' +
                            '<label for="edit_section_content_fallback">Content (JSON)</label>' +
                            '<textarea class="form-control" id="edit_section_content_fallback" rows="10">' +
                            JSON.stringify(response.section.content, null, 2) +
                            '</textarea></div>'
                        );
                        $('#edit-json-view-container').hide();
                    }

                    // Set form action
                    $('#editSectionForm').attr('action', '{{ route("admin.pages.sections.update", ["page" => $page->id, "section" => "__SECTION_ID__"]) }}'.replace('__SECTION_ID__', sectionId));

                    // Show modal
                    $('#editSectionModal').modal('show');
                },
                error: function(xhr) {
                    alert('Failed to load section data');
                }
            });
        };

        // Handle Edit Section Form submission
        $('#editSectionForm').on('submit', function(e) {
            e.preventDefault();

            const form = $(this);
            const sectionId = $('#edit_section_id').val();
            const isVisible = $('#edit_is_visible').is(':checked');

            // Collect data from dynamic form or fallback textarea
            let contentObj;
            if ($('#edit_section_content_fallback').length) {
                // Use fallback JSON textarea if no fields defined
                try {
                    contentObj = JSON.parse($('#edit_section_content_fallback').val());
                } catch (err) {
                    alert('Invalid JSON format. Please check your content.\n\nError: ' + err.message);
                    return;
                }
            } else {
                // Collect from dynamic fields in edit modal
                contentObj = collectFormData('edit-dynamic-fields-container');
            }

            // Submit via AJAX
            $.ajax({
                url: form.attr('action'),
                method: 'PUT',
                data: {
                    _token: $('input[name="_token"]', form).val(),
                    content: contentObj,
                    is_visible: isVisible ? 1 : 0
                },
                success: function(response) {
                    if (response.success) {
                        // Reload page to show updated section
                        location.reload();
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'Failed to update section.';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMsg = Object.values(xhr.responseJSON.errors).flat().join('\n');
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    alert(errorMsg);
                }
            });
        });

        // JSON View Toggle for Edit Modal
        $('#edit-show-json-view').on('change', function() {
            if ($(this).is(':checked')) {
                const jsonData = collectFormData('edit-dynamic-fields-container');
                $('#edit_section_content_json').val(JSON.stringify(jsonData, null, 2)).show();
            } else {
                $('#edit_section_content_json').hide();
            }
        });

        // Update JSON view when edit fields change
        $(document).on('input change', '#edit-dynamic-fields-container .dynamic-field', function() {
            if ($('#edit-show-json-view').is(':checked')) {
                const jsonData = collectFormData('edit-dynamic-fields-container');
                $('#edit_section_content_json').val(JSON.stringify(jsonData, null, 2));
            }
        });

        // Move Section Function
        window.moveSection = function(sectionId, direction) {
            // Get all sections
            const $sections = $('#sections-container .section-item');
            const $currentSection = $sections.filter('[data-section-id="' + sectionId + '"]');
            const currentIndex = $sections.index($currentSection);

            let newOrder = [];
            $sections.each(function(index) {
                const id = $(this).data('section-id');
                if (index === currentIndex) {
                    if (direction === 'up' && index > 0) {
                        // Swap with previous
                        newOrder[index - 1] = id;
                    } else if (direction === 'down' && index < $sections.length - 1) {
                        // Will be placed after next
                        newOrder[index + 1] = id;
                    } else {
                        newOrder[index] = id;
                    }
                } else if (direction === 'up' && index === currentIndex - 1) {
                    // Previous moves down
                    newOrder[index + 1] = $(this).data('section-id');
                } else if (direction === 'down' && index === currentIndex + 1) {
                    // Next moves up
                    newOrder[index - 1] = $(this).data('section-id');
                } else {
                    newOrder[index] = id;
                }
            });

            // Send reorder request
            $.ajax({
                url: '{{ route("admin.pages.sections.reorder", $page->id) }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    order: newOrder
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    }
                },
                error: function(xhr) {
                    alert('Failed to reorder sections');
                }
            });
        };

        // Delete Section Function
        window.deleteSection = function(sectionId) {
            if (confirm('Are you sure you want to delete this section? This action cannot be undone.')) {
                $.ajax({
                    url: '{{ route("admin.pages.sections.destroy", ["page" => $page->id, "section" => "__SECTION_ID__"]) }}'.replace('__SECTION_ID__', sectionId),
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Failed to delete section.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        alert(errorMsg);
                    }
                });
            }
        };
    </script>
@endpush

@push('custom-styles')
<style>
    /* Repeater Field Styling */
    .repeater-container {
        border: 1px dashed #ddd;
        border-radius: 4px;
        padding: 15px;
        background-color: #f8f9fa;
    }

    .repeater-items {
        max-height: 500px;
        overflow-y: auto;
    }

    .repeater-item {
        border-left: 3px solid #6777ef;
    }

    .repeater-item .card-header {
        cursor: move;
    }

    .repeater-item .card-body .form-group:last-child {
        margin-bottom: 0 !important;
    }

    /* JSON/Monospace textarea */
    .font-monospace {
        font-family: 'Courier New', Courier, monospace;
        font-size: 12px;
    }

    /* Section Item Styling */
    .section-item {
        border-left: 4px solid #6777ef;
    }

    .section-item pre {
        background-color: #f4f4f4;
        border-radius: 4px;
        padding: 10px;
        max-height: 200px;
        overflow-y: auto;
        font-size: 11px;
    }

    .section-item:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    /* Modal Improvements */
    .modal-lg {
        max-width: 900px;
    }

    #dynamic-fields-container,
    #edit-dynamic-fields-container {
        max-height: 500px;
        overflow-y: auto;
        padding-right: 10px;
    }

    /* Template Selector */
    #section_template_id {
        font-weight: 500;
    }

    #section_template_id option {
        padding: 10px;
    }

    /* Template Card Styling */
    .template-grid {
        max-height: 400px;
        overflow-y: auto;
        padding-right: 10px;
    }

    .template-card {
        border: 2px solid #e3e3e3;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
    }

    .template-card:hover {
        border-color: #6777ef;
        box-shadow: 0 4px 12px rgba(103, 119, 239, 0.15);
        transform: translateY(-2px);
    }

    .template-card.active {
        border-color: #6777ef;
        box-shadow: 0 4px 12px rgba(103, 119, 239, 0.3);
        background-color: #f0f3ff;
    }

    .template-card-preview {
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .template-card-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .template-card-icon {
        opacity: 0.8;
    }

    .template-card-body {
        padding: 12px;
        text-align: center;
    }

    .template-card-body h6 {
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 4px;
        color: #333;
    }

    .template-card.active .template-card-body h6 {
        color: #6777ef;
    }

    /* Template Selector Tabs */
    .template-selector-tabs .nav-pills .nav-link {
        padding: 8px 20px;
        font-size: 14px;
        border-radius: 20px;
    }

    .template-selector-tabs .nav-pills .nav-link.active {
        background-color: #6777ef;
    }
</style>
@endpush
