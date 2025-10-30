@extends('admin.layouts.app')

@section('title', 'Create Menu')
@section('page-title', 'Create New Menu')

@section('breadcrumb')
    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
    <div class="breadcrumb-item"><a href="{{ route('admin.menus.index') }}">Menus</a></div>
    <div class="breadcrumb-item active">Create</div>
@endsection

@section('content')
    <form action="{{ route('admin.menus.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Menu Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Menu Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   placeholder="e.g., Main Menu, Footer Menu"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Give your menu a descriptive name for easy identification
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="3"
                                      placeholder="Brief description of this menu">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="location">Menu Location</label>
                            <select class="form-control @error('location') is-invalid @enderror"
                                    id="location"
                                    name="location">
                                <option value="">Select Location</option>
                                <option value="primary" {{ old('location') == 'primary' ? 'selected' : '' }}>
                                    Primary Menu (Main navigation)
                                </option>
                                <option value="footer" {{ old('location') == 'footer' ? 'selected' : '' }}>
                                    Footer Menu
                                </option>
                                <option value="sidebar" {{ old('location') == 'sidebar' ? 'selected' : '' }}>
                                    Sidebar Menu
                                </option>
                                <option value="secondary" {{ old('location') == 'secondary' ? 'selected' : '' }}>
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
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Note:</strong> You can add menu items after creating the menu.
                            Menu items will allow you to link to pages, posts, categories, or custom URLs.
                        </div>

                        <div id="menu-items-container">
                            <!-- Menu items will be added here after creation -->
                        </div>
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
                                <input type="checkbox"
                                       class="custom-control-input"
                                       id="is_active"
                                       name="is_active"
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
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
                                <i class="fas fa-save"></i> Create Menu
                            </button>
                            <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary btn-lg btn-block">
                                <i class="fas fa-times"></i> Cancel
                            </a>
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
                        <h6 class="text-primary">What are menus?</h6>
                        <p class="text-muted small mb-3">
                            Menus are navigation structures that help visitors browse your site.
                            They can contain links to pages, posts, categories, or custom URLs.
                        </p>

                        <h6 class="text-primary">Menu Locations</h6>
                        <p class="text-muted small mb-3">
                            Different locations display menus in different areas of your site.
                            Common locations include primary navigation, footer, and sidebar.
                        </p>

                        <h6 class="text-primary">Next Steps</h6>
                        <p class="text-muted small mb-0">
                            After creating the menu, you'll be able to add menu items and organize them
                            with drag-and-drop functionality.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
