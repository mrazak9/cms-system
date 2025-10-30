@extends('admin.layouts.app')

@section('title', 'Profile Settings')
@section('page-title', 'Profile Settings')

@section('breadcrumb')
    <div class="breadcrumb-item active">Profile</div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-md-12 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Profile</h4>
                </div>
                <div class="card-body">
                    @if(session('status') === 'profile-updated')
                        <div class="alert alert-success alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                                Profile updated successfully!
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $user->name) }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email', $user->email) }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if ($user->email_verified_at === null)
                                <small class="form-text text-warning">
                                    Your email address is unverified.
                                </small>
                            @endif
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4>Update Password</h4>
                </div>
                <div class="card-body">
                    @if(session('status') === 'password-updated')
                        <div class="alert alert-success alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                                Password updated successfully!
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password"
                                   class="form-control @error('current_password') is-invalid @enderror"
                                   id="current_password"
                                   name="current_password"
                                   required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">New Password</label>
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password"
                                   name="password"
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirm New Password</label>
                            <input type="password"
                                   class="form-control"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   required>
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-key"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-12 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4>Profile Information</h4>
                </div>
                <div class="card-body text-center">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=200&background=6777ef&color=fff"
                         class="rounded-circle mb-3"
                         alt="Profile Picture"
                         style="width: 150px; height: 150px;">
                    <h5 class="mb-1">{{ $user->name }}</h5>
                    <p class="text-muted">{{ $user->email }}</p>

                    @if($user->roles->isNotEmpty())
                        <div class="mt-3">
                            @foreach($user->roles as $role)
                                <span class="badge badge-primary">{{ ucfirst($role->name) }}</span>
                            @endforeach
                        </div>
                    @endif

                    <div class="mt-4 text-left">
                        <div class="text-small text-muted mb-1">Member Since</div>
                        <div class="font-weight-bold">{{ $user->created_at->format('F d, Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
