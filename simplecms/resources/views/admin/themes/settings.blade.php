@extends('admin.layouts.app')

@section('title', 'Theme Content Settings')
@section('page-title', $theme->name . ' - Content Settings')

@section('breadcrumb')
    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
    <div class="breadcrumb-item"><a href="{{ route('admin.themes.index') }}">Themes</a></div>
    <div class="breadcrumb-item active">{{ $theme->name }} Settings</div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-cog"></i> Edit Theme Content</h4>
                    <div class="card-header-action">
                        <a href="{{ route('admin.themes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Themes
                        </a>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addSettingModal">
                            <i class="fas fa-plus"></i> Add New Setting
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Info:</strong> Edit the content that will appear on the homepage when this theme is active.
                        Changes will take effect immediately after saving.
                    </div>

                    <form action="{{ route('admin.themes.settings.update', $theme->id) }}" method="POST">
                        @csrf

                        @if($settingsGrouped->isEmpty())
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                No settings found for this theme. Click "Add New Setting" to create content fields.
                            </div>
                        @else
                            @foreach($settingsGrouped as $group => $groupSettings)
                                <div class="card mb-3">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">
                                            <i class="fas fa-layer-group"></i>
                                            {{ ucwords(str_replace('_', ' ', $group)) }} Section
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @foreach($groupSettings as $setting)
                                                <div class="col-md-6 mb-3">
                                                    <label for="setting_{{ $setting->key }}" class="form-label">
                                                        <strong>{{ ucwords(str_replace('_', ' ', $setting->key)) }}</strong>
                                                        <small class="text-muted">({{ $setting->type }})</small>
                                                    </label>

                                                    @if($setting->type === 'textarea')
                                                        <textarea
                                                            name="settings[{{ $setting->key }}]"
                                                            id="setting_{{ $setting->key }}"
                                                            class="form-control @error('settings.' . $setting->key) is-invalid @enderror"
                                                            rows="3"
                                                        >{{ old('settings.' . $setting->key, $setting->value) }}</textarea>
                                                    @else
                                                        <input
                                                            type="{{ $setting->type === 'number' ? 'number' : 'text' }}"
                                                            name="settings[{{ $setting->key }}]"
                                                            id="setting_{{ $setting->key }}"
                                                            class="form-control @error('settings.' . $setting->key) is-invalid @enderror"
                                                            value="{{ old('settings.' . $setting->key, $setting->value) }}"
                                                        >
                                                    @endif

                                                    @error('settings.' . $setting->key)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror

                                                    <small class="form-text text-muted">Key: {{ $setting->key }}</small>

                                                    {{-- Delete button --}}
                                                    <button type="button" class="btn btn-sm btn-danger mt-2"
                                                        onclick="deleteSetting({{ $setting->id }}, '{{ $setting->key }}')">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        @if(!$settingsGrouped->isEmpty())
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save"></i> Save All Changes
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Add New Setting Modal --}}
    <div class="modal fade" id="addSettingModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.themes.settings.store', $theme->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Setting</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="key">Key <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('key') is-invalid @enderror"
                                id="key" name="key" value="{{ old('key') }}"
                                placeholder="e.g., hero_title, service_1_description"
                                required>
                            @error('key')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Use lowercase with underscores (snake_case)</small>
                        </div>

                        <div class="form-group">
                            <label for="value">Value <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('value') is-invalid @enderror"
                                id="value" name="value" rows="3" required>{{ old('value') }}</textarea>
                            @error('value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="type">Type <span class="text-danger">*</span></label>
                            <select class="form-control @error('type') is-invalid @enderror"
                                id="type" name="type" required>
                                <option value="text" {{ old('type') === 'text' ? 'selected' : '' }}>Text</option>
                                <option value="textarea" {{ old('type') === 'textarea' ? 'selected' : '' }}>Textarea</option>
                                <option value="image" {{ old('type') === 'image' ? 'selected' : '' }}>Image</option>
                                <option value="url" {{ old('type') === 'url' ? 'selected' : '' }}>URL</option>
                                <option value="number" {{ old('type') === 'number' ? 'selected' : '' }}>Number</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="group">Group <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('group') is-invalid @enderror"
                                id="group" name="group" value="{{ old('group') }}"
                                placeholder="e.g., hero, services, features"
                                required>
                            @error('group')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Group settings by section (hero, services, etc.)</small>
                        </div>

                        <div class="form-group">
                            <label for="order">Order</label>
                            <input type="number" class="form-control @error('order') is-invalid @enderror"
                                id="order" name="order" value="{{ old('order', 0) }}">
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Display order (optional, default: 0)</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-plus"></i> Add Setting
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete forms --}}
    @foreach($settingsGrouped as $group => $groupSettings)
        @foreach($groupSettings as $setting)
            <form id="delete-form-{{ $setting->id }}"
                action="{{ route('admin.themes.settings.destroy', [$theme->id, $setting->id]) }}"
                method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        @endforeach
    @endforeach
@endsection

@push('custom-scripts')
<script>
    function deleteSetting(id, key) {
        if (confirm(`Are you sure you want to delete the setting "${key}"? This action cannot be undone.`)) {
            document.getElementById('delete-form-' + id).submit();
        }
    }

    // Auto-show modal if there are validation errors
    @if ($errors->any())
        $('#addSettingModal').modal('show');
    @endif
</script>
@endpush
