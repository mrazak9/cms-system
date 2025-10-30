@extends('admin.layouts.app')

@section('title', 'Edit Menu')
@section('page-title', 'Edit Menu')

@section('breadcrumb')
    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
    <div class="breadcrumb-item"><a href="{{ route('admin.menus.index') }}">Menus</a></div>
    <div class="breadcrumb-item active">Edit</div>
@endsection

@section('content')
    <form action="{{ route('admin.menus.update', $menu) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Menu Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Menu Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name', $menu->name) }}"
                                placeholder="e.g., Main Menu, Footer Menu" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Give your menu a descriptive name for easy identification
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                rows="3" placeholder="Brief description of this menu">{{ old('description', $menu->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="location">Menu Location</label>
                            <select class="form-control @error('location') is-invalid @enderror" id="location"
                                name="location">
                                <option value="">Select Location</option>
                                <option value="primary"
                                    {{ old('location', $menu->location) == 'primary' ? 'selected' : '' }}>
                                    Primary Menu (Main navigation)
                                </option>
                                <option value="footer" {{ old('location', $menu->location) == 'footer' ? 'selected' : '' }}>
                                    Footer Menu
                                </option>
                                <option value="sidebar"
                                    {{ old('location', $menu->location) == 'sidebar' ? 'selected' : '' }}>
                                    Sidebar Menu
                                </option>
                                <option value="secondary"
                                    {{ old('location', $menu->location) == 'secondary' ? 'selected' : '' }}>
                                    Secondary Menu
                                </option>
                            </select>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Where should this menu be displayed on your site?
                            </small>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4>Menu Items</h4>
                        <div class="card-header-action">
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                data-target="#addMenuItemModal">
                                <i class="fas fa-plus"></i> Add Item
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (isset($menu->allItems) && count($menu->allItems) > 0)
                            <div id="menu-items-container" class="nested-sortable">
                                @foreach ($menu->allItems->whereNull('parent_id')->sortBy('order') as $item)
                                    @include('admin.menus.partials.menu-item', ['item' => $item, 'level' => 0])
                                @endforeach
                            </div>
                            <small class="form-text text-muted mt-2">
                                <i class="fas fa-info-circle"></i> Drag items to reorder them or nest them by dragging to the right
                            </small>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                This menu has no items yet. Click "Add Item" to create your first menu item.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Menu Options</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active"
                                    value="1" {{ old('is_active', $menu->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">
                                    <strong>Active</strong>
                                    <br>
                                    <small class="text-muted">Make this menu visible on the site</small>
                                </label>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary btn-lg btn-block">
                                <i class="fas fa-save"></i> Update Menu
                            </button>
                            <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary btn-lg btn-block">
                                <i class="fas fa-arrow-left"></i> Back to Menus
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4>Menu Statistics</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 text-center">
                                <h4 class="text-primary">{{ $menu->allItems->count() ?? 0 }}</h4>
                                <small class="text-muted">Total Items</small>
                            </div>
                            <div class="col-6 text-center">
                                <h4 class="text-success">{{ $menu->created_at->format('M d, Y') }}</h4>
                                <small class="text-muted">Created</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4>
                            <i class="fas fa-question-circle"></i> Help
                        </h4>
                    </div>
                    <div class="card-body">
                        <h6 class="text-primary">Adding Items</h6>
                        <p class="text-muted small mb-3">
                            Click "Add Item" to create new menu links. You can link to pages,
                            posts, categories, or create custom URLs.
                        </p>

                        <h6 class="text-primary">Reordering Items</h6>
                        <p class="text-muted small mb-3">
                            Drag and drop menu items to change their order. The changes will be saved
                            automatically.
                        </p>

                        <h6 class="text-primary">Menu Location</h6>
                        <p class="text-muted small mb-0">
                            The menu location determines where the menu appears on your site.
                            Make sure your theme supports the selected location.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>


@endsection
<!-- Add Menu Item Modal -->
<div class="modal fade" id="addMenuItemModal" tabindex="-1" role="dialog" aria-labelledby="addMenuItemModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.menus.items.store', $menu) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addMenuItemModalLabel">Add Menu Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="item_label">Label <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('label') is-invalid @enderror"
                            id="item_label" name="label" value="{{ old('label') }}" placeholder="Menu item text"
                            required>
                        @error('label')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="item_type">Link Type <span class="text-danger">*</span></label>
                        <select class="form-control @error('type') is-invalid @enderror" id="item_type"
                            name="type" required onchange="toggleMenuItemFields()">
                            <option value="custom">Custom URL</option>
                            <option value="page">Page</option>
                            <option value="post">Post</option>
                            <option value="category">Category</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Custom URL Field -->
                    <div class="form-group" id="custom_url_field">
                        <label for="item_url">URL <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('url') is-invalid @enderror" id="item_url"
                            name="url" value="{{ old('url') }}" placeholder="/about or https://example.com">
                        @error('url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Page Select Field -->
                    <div class="form-group" id="page_select_field" style="display: none;">
                        <label for="item_page_id">Select Page <span class="text-danger">*</span></label>
                        <select class="form-control @error('page_id') is-invalid @enderror" id="item_page_id"
                            name="page_id">
                            <option value="">-- Select Page --</option>
                            @foreach($pages as $page)
                                <option value="{{ $page->id }}" data-url="{{ $page->slug }}">{{ $page->title }}</option>
                            @endforeach
                        </select>
                        @error('page_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Post Select Field -->
                    <div class="form-group" id="post_select_field" style="display: none;">
                        <label for="item_post_id">Select Post <span class="text-danger">*</span></label>
                        <select class="form-control @error('post_id') is-invalid @enderror" id="item_post_id"
                            name="post_id">
                            <option value="">-- Select Post --</option>
                            @foreach($posts as $post)
                                <option value="{{ $post->id }}" data-url="{{ $post->slug }}">{{ $post->title }}</option>
                            @endforeach
                        </select>
                        @error('post_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Category Select Field -->
                    <div class="form-group" id="category_select_field" style="display: none;">
                        <label for="item_category_id">Select Category <span class="text-danger">*</span></label>
                        <select class="form-control @error('category_id') is-invalid @enderror" id="item_category_id"
                            name="category_id">
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" data-url="{{ $category->slug }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="item_parent_id">Parent Item (Optional)</label>
                                <select class="form-control @error('parent_id') is-invalid @enderror" id="item_parent_id"
                                    name="parent_id">
                                    <option value="">-- No Parent (Top Level) --</option>
                                    @foreach($menu->allItems->sortBy('order') as $existingItem)
                                        <option value="{{ $existingItem->id }}">{{ $existingItem->title }}</option>
                                    @endforeach
                                </select>
                                @error('parent_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Create a sub-menu by selecting a parent item</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="item_order">Order</label>
                                <input type="number" class="form-control @error('order') is-invalid @enderror"
                                    id="item_order" name="order" value="0" min="0">
                                @error('order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Lower numbers appear first</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="item_target">Open Link In</label>
                        <select class="form-control @error('target') is-invalid @enderror" id="item_target"
                            name="target">
                            <option value="_self">Same Window</option>
                            <option value="_blank">New Window</option>
                        </select>
                        @error('target')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="item_css_class">CSS Class (Optional)</label>
                        <input type="text" class="form-control @error('css_class') is-invalid @enderror"
                            id="item_css_class" name="css_class" placeholder="custom-class another-class">
                        @error('css_class')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Add custom CSS classes for styling</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Menu Item Modal -->
<div class="modal fade" id="editMenuItemModal" tabindex="-1" role="dialog" aria-labelledby="editMenuItemModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editMenuItemForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editMenuItemModalLabel">Edit Menu Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_item_id" name="item_id">

                    <div class="form-group">
                        <label for="edit_item_label">Label <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_item_label" name="label" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_item_url">URL <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_item_url" name="url" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_item_parent_id">Parent Item</label>
                                <select class="form-control" id="edit_item_parent_id" name="parent_id">
                                    <option value="">-- No Parent (Top Level) --</option>
                                    @foreach($menu->allItems->sortBy('order') as $existingItem)
                                        <option value="{{ $existingItem->id }}">{{ $existingItem->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_item_order">Order</label>
                                <input type="number" class="form-control" id="edit_item_order" name="order" value="0" min="0">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_item_target">Open Link In</label>
                        <select class="form-control" id="edit_item_target" name="target">
                            <option value="_self">Same Window</option>
                            <option value="_blank">New Window</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit_item_css_class">CSS Class</label>
                        <input type="text" class="form-control" id="edit_item_css_class" name="css_class">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .nested-sortable {
            min-height: 50px;
        }

        .menu-item {
            position: relative;
        }

        .menu-item-content {
            position: relative;
        }

        .drag-handle {
            cursor: move !important;
        }

        .menu-item-children {
            position: relative;
        }

        .sortable-ghost {
            opacity: 0.4;
            background: #f8f9fa;
        }

        .sortable-drag {
            opacity: 0.8;
        }

        .sortable-chosen {
            background: #e3f2fd;
        }
    </style>
@endpush

@push('custom-scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        // Toggle menu item fields based on type selection
        function toggleMenuItemFields() {
            const type = document.getElementById('item_type').value;
            const customUrlField = document.getElementById('custom_url_field');
            const pageSelectField = document.getElementById('page_select_field');
            const postSelectField = document.getElementById('post_select_field');
            const categorySelectField = document.getElementById('category_select_field');
            const urlInput = document.getElementById('item_url');
            const pageSelect = document.getElementById('item_page_id');
            const postSelect = document.getElementById('item_post_id');
            const categorySelect = document.getElementById('item_category_id');

            // Hide all fields first
            customUrlField.style.display = 'none';
            pageSelectField.style.display = 'none';
            postSelectField.style.display = 'none';
            categorySelectField.style.display = 'none';

            // Clear required attributes
            urlInput.removeAttribute('required');
            pageSelect.removeAttribute('required');
            postSelect.removeAttribute('required');
            categorySelect.removeAttribute('required');

            // Show appropriate field based on type
            if (type === 'custom') {
                customUrlField.style.display = 'block';
                urlInput.setAttribute('required', 'required');
            } else if (type === 'page') {
                pageSelectField.style.display = 'block';
                pageSelect.setAttribute('required', 'required');
            } else if (type === 'post') {
                postSelectField.style.display = 'block';
                postSelect.setAttribute('required', 'required');
            } else if (type === 'category') {
                categorySelectField.style.display = 'block';
                categorySelect.setAttribute('required', 'required');
            }
        }

        // Auto-fill URL when page/post/category is selected
        document.addEventListener('DOMContentLoaded', function() {
            const pageSelect = document.getElementById('item_page_id');
            const postSelect = document.getElementById('item_post_id');
            const categorySelect = document.getElementById('item_category_id');
            const urlInput = document.getElementById('item_url');

            pageSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.dataset.url) {
                    urlInput.value = selectedOption.dataset.url;
                }
            });

            postSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.dataset.url) {
                    urlInput.value = selectedOption.dataset.url;
                }
            });

            categorySelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.dataset.url) {
                    urlInput.value = selectedOption.dataset.url;
                }
            });

            // Initialize on page load
            toggleMenuItemFields();
        });

        function deleteMenuItem(id) {
            if (confirm('Are you sure you want to delete this menu item?')) {
                // Create form and submit
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("admin.menus.items.destroy", ["menu" => $menu->id, "item" => "ITEM_ID"]) }}'.replace('ITEM_ID', id);

                let csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';

                let methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';

                form.appendChild(csrfInput);
                form.appendChild(methodInput);

                document.body.appendChild(form);
                form.submit();
            }
        }

        function editMenuItem(id) {
            // Get menu item data via AJAX
            fetch(`/admin/menus/{{ $menu->id }}/items/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const item = data.item;

                        // Fill the form with item data
                        document.getElementById('edit_item_id').value = item.id;
                        document.getElementById('edit_item_label').value = item.title;
                        document.getElementById('edit_item_url').value = item.url || '';
                        document.getElementById('edit_item_target').value = item.target || '_self';
                        document.getElementById('edit_item_parent_id').value = item.parent_id || '';
                        document.getElementById('edit_item_order').value = item.order || 0;
                        document.getElementById('edit_item_css_class').value = item.css_class || '';

                        // Update form action URL
                        document.getElementById('editMenuItemForm').action =
                            '{{ route("admin.menus.items.update", ["menu" => $menu->id, "item" => "ITEM_ID"]) }}'
                            .replace('ITEM_ID', id);

                        // Show the modal
                        $('#editMenuItemModal').modal('show');
                    }
                })
                .catch(error => {
                    console.error('Error fetching menu item:', error);
                    alert('Failed to load menu item data');
                });
        }

        // Reset form when modal is closed
        $('#addMenuItemModal').on('hidden.bs.modal', function() {
            const form = $(this).find('form')[0];
            form.reset();
            toggleMenuItemFields(); // Reset to default view
        });

        // Drag and drop reordering using SortableJS with nested support
        @if (isset($menu->allItems) && count($menu->allItems) > 0)
            function initSortable() {
                const menuItemsContainer = document.getElementById('menu-items-container');
                if (!menuItemsContainer) return;

                // Initialize sortable for main container
                new Sortable(menuItemsContainer, {
                    group: 'nested',
                    animation: 150,
                    fallbackOnBody: true,
                    swapThreshold: 0.65,
                    handle: '.drag-handle',
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    dragClass: 'sortable-drag',
                    onEnd: function(evt) {
                        updateMenuStructure();
                    }
                });

                // Initialize sortable for all children containers
                const childrenContainers = document.querySelectorAll('.menu-item-children');
                childrenContainers.forEach(function(container) {
                    new Sortable(container, {
                        group: 'nested',
                        animation: 150,
                        fallbackOnBody: true,
                        swapThreshold: 0.65,
                        handle: '.drag-handle',
                        ghostClass: 'sortable-ghost',
                        chosenClass: 'sortable-chosen',
                        dragClass: 'sortable-drag',
                        onEnd: function(evt) {
                            updateMenuStructure();
                        }
                    });
                });
            }

            function updateMenuStructure() {
                const structure = [];

                function parseContainer(container, parentId = null) {
                    const items = container.children;
                    Array.from(items).forEach(function(item, index) {
                        if (item.classList.contains('menu-item')) {
                            const itemId = item.dataset.id;
                            structure.push({
                                id: itemId,
                                parent_id: parentId,
                                order: index
                            });

                            // Check for children
                            const childrenContainer = item.querySelector(':scope > .menu-item-children');
                            if (childrenContainer && childrenContainer.children.length > 0) {
                                parseContainer(childrenContainer, itemId);
                            }
                        }
                    });
                }

                const mainContainer = document.getElementById('menu-items-container');
                parseContainer(mainContainer, null);

                // Send AJAX request to update structure
                fetch('{{ route("admin.menus.items.reorder", $menu) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ structure: structure })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Menu structure updated successfully');
                    } else {
                        alert('Failed to update menu structure');
                    }
                })
                .catch(error => {
                    console.error('Error updating menu structure:', error);
                    alert('Failed to update menu structure');
                });
            }

            // Initialize sortable when DOM is ready
            initSortable();
        @endif

        // Auto-show modal if there are validation errors for menu items
        @if ($errors->has('label') || $errors->has('type') || $errors->has('url') || $errors->has('page_id') || $errors->has('post_id'))
            $('#addMenuItemModal').modal('show');
        @endif
    </script>
@endpush
