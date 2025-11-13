@extends('admin.layouts.app')

@section('title', 'Add Redirect')

@section('content')
<div class="section-header">
    <h1>Add New Redirect</h1>
</div>

<div class="section-body">
    <form action="{{ route('admin.redirects.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label>From URL <span class="text-danger">*</span></label>
                            <input type="text" name="from_url" class="form-control @error('from_url') is-invalid @enderror"
                                   value="{{ old('from_url') }}" placeholder="/old-url" required>
                            @error('from_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">The URL path to redirect FROM (e.g., /old-page)</small>
                        </div>

                        <div class="form-group">
                            <label>To URL <span class="text-danger">*</span></label>
                            <input type="text" name="to_url" class="form-control @error('to_url') is-invalid @enderror"
                                   value="{{ old('to_url') }}" placeholder="/new-url or https://example.com" required>
                            @error('to_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">The URL to redirect TO (can be relative or absolute)</small>
                        </div>

                        <div class="form-group">
                            <label>Redirect Type <span class="text-danger">*</span></label>
                            <select name="status_code" class="form-control @error('status_code') is-invalid @enderror" required>
                                <option value="301" {{ old('status_code', 301) == 301 ? 'selected' : '' }}>301 - Permanent</option>
                                <option value="302" {{ old('status_code') == 302 ? 'selected' : '' }}>302 - Temporary</option>
                            </select>
                            @error('status_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">301 for permanent redirects, 302 for temporary</small>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Active</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Create Redirect</button>
                            <a href="{{ route('admin.redirects.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
