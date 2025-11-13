@extends('admin.layouts.app')

@section('title', 'Edit Redirect')

@section('content')
<div class="section-header">
    <h1>Edit Redirect</h1>
</div>

<div class="section-body">
    <form action="{{ route('admin.redirects.update', $redirect) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label>From URL <span class="text-danger">*</span></label>
                            <input type="text" name="from_url" class="form-control @error('from_url') is-invalid @enderror"
                                   value="{{ old('from_url', $redirect->from_url) }}" required>
                            @error('from_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>To URL <span class="text-danger">*</span></label>
                            <input type="text" name="to_url" class="form-control @error('to_url') is-invalid @enderror"
                                   value="{{ old('to_url', $redirect->to_url) }}" required>
                            @error('to_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Redirect Type <span class="text-danger">*</span></label>
                            <select name="status_code" class="form-control" required>
                                <option value="301" {{ old('status_code', $redirect->status_code) == 301 ? 'selected' : '' }}>301 - Permanent</option>
                                <option value="302" {{ old('status_code', $redirect->status_code) == 302 ? 'selected' : '' }}>302 - Temporary</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $redirect->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Active</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="alert alert-info">
                                <strong>Stats:</strong> This redirect has been used {{ number_format($redirect->hits) }} times.
                                @if($redirect->last_used_at)
                                    Last used {{ $redirect->last_used_at->diffForHumans() }}.
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Update Redirect</button>
                            <a href="{{ route('admin.redirects.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
