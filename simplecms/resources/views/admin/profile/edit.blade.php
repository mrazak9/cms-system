@extends('admin.layouts.app')

@section('title', 'Edit Profile')
@section('page-title', 'Edit Profile')

@section('breadcrumb')
    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
    <div class="breadcrumb-item active">Edit Profile</div>
@endsection

@section('content')
    @if(session('status') === 'profile-updated')
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
                <i class="fas fa-check-circle me-2"></i>Profile updated successfully!
            </div>
        </div>
    @endif

    @if(session('status') === 'password-updated')
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
                <i class="fas fa-check-circle me-2"></i>Password updated successfully!
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <!-- Basic Information -->
                <div class="card">
                    <div class="card-header">
                        <h4>Basic Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Full Name <span class="text-danger">*</span></label>
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
                            <label for="email">Email Address <span class="text-danger">*</span></label>
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
                                    <i class="fas fa-exclamation-triangle me-1"></i>Your email address is unverified.
                                </small>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="bio">Bio</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror"
                                      id="bio"
                                      name="bio"
                                      rows="4"
                                      placeholder="Tell us about yourself...">{{ old('bio', $user->bio) }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Maximum 1000 characters</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="text"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           id="phone"
                                           name="phone"
                                           value="{{ old('phone', $user->phone) }}"
                                           placeholder="+1234567890">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="location">Location</label>
                                    <input type="text"
                                           class="form-control @error('location') is-invalid @enderror"
                                           id="location"
                                           name="location"
                                           value="{{ old('location', $user->location) }}"
                                           placeholder="City, Country">
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Media Links -->
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-share-alt me-2"></i>Social Media Links</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="social_twitter"><i class="fab fa-twitter text-info me-2"></i>Twitter</label>
                            <input type="url"
                                   class="form-control @error('social_twitter') is-invalid @enderror"
                                   id="social_twitter"
                                   name="social_twitter"
                                   value="{{ old('social_twitter', $user->getSocialLink('twitter')) }}"
                                   placeholder="https://twitter.com/username">
                            @error('social_twitter')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="social_facebook"><i class="fab fa-facebook text-primary me-2"></i>Facebook</label>
                            <input type="url"
                                   class="form-control @error('social_facebook') is-invalid @enderror"
                                   id="social_facebook"
                                   name="social_facebook"
                                   value="{{ old('social_facebook', $user->getSocialLink('facebook')) }}"
                                   placeholder="https://facebook.com/username">
                            @error('social_facebook')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="social_linkedin"><i class="fab fa-linkedin text-primary me-2"></i>LinkedIn</label>
                            <input type="url"
                                   class="form-control @error('social_linkedin') is-invalid @enderror"
                                   id="social_linkedin"
                                   name="social_linkedin"
                                   value="{{ old('social_linkedin', $user->getSocialLink('linkedin')) }}"
                                   placeholder="https://linkedin.com/in/username">
                            @error('social_linkedin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="social_github"><i class="fab fa-github text-dark me-2"></i>GitHub</label>
                            <input type="url"
                                   class="form-control @error('social_github') is-invalid @enderror"
                                   id="social_github"
                                   name="social_github"
                                   value="{{ old('social_github', $user->getSocialLink('github')) }}"
                                   placeholder="https://github.com/username">
                            @error('social_github')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="social_instagram"><i class="fab fa-instagram text-danger me-2"></i>Instagram</label>
                            <input type="url"
                                   class="form-control @error('social_instagram') is-invalid @enderror"
                                   id="social_instagram"
                                   name="social_instagram"
                                   value="{{ old('social_instagram', $user->getSocialLink('instagram')) }}"
                                   placeholder="https://instagram.com/username">
                            @error('social_instagram')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="social_website"><i class="fas fa-globe text-success me-2"></i>Website</label>
                            <input type="url"
                                   class="form-control @error('social_website') is-invalid @enderror"
                                   id="social_website"
                                   name="social_website"
                                   value="{{ old('social_website', $user->getSocialLink('website')) }}"
                                   placeholder="https://yourwebsite.com">
                            @error('social_website')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Avatar Upload -->
                <div class="card">
                    <div class="card-header">
                        <h4>Profile Picture</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <img src="{{ $user->getAvatarUrl() }}"
                                     alt="{{ $user->name }}"
                                     class="rounded-circle mb-3"
                                     style="width: 150px; height: 150px; object-fit: cover;"
                                     id="avatar-preview">
                            </div>
                            <div class="col-md-8">
                                @if($user->avatar)
                                    <div class="form-group">
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox"
                                                   class="custom-control-input"
                                                   name="remove_avatar"
                                                   value="1"
                                                   id="remove_avatar">
                                            <span class="custom-control-label text-danger">Remove current avatar</span>
                                        </label>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label for="avatar">Upload New Avatar</label>
                                    <input type="file"
                                           class="form-control-file @error('avatar') is-invalid @enderror"
                                           id="avatar"
                                           name="avatar"
                                           accept="image/*">
                                    @error('avatar')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Max size: 2MB. Formats: JPG, PNG, GIF
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-2"></i>Update Profile
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-arrow-left me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>

        <div class="col-lg-4">
            <!-- Password Change -->
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-lock me-2"></i>Security</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted">Ensure your account is using a secure password.</p>
                    <a href="{{ route('admin.profile.password.edit') }}" class="btn btn-warning btn-block">
                        <i class="fas fa-key me-2"></i>Change Password
                    </a>
                </div>
            </div>

            <!-- Account Statistics -->
            <div class="card">
                <div class="card-header">
                    <h4>Account Info</h4>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><i class="fas fa-user-tag text-primary me-2"></i><strong>Role:</strong></td>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="badge badge-primary">{{ ucfirst($role->name) }}</span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-calendar text-success me-2"></i><strong>Member Since:</strong></td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                        </tr>
                        @if($user->posts()->count() > 0)
                            <tr>
                                <td><i class="fas fa-newspaper text-info me-2"></i><strong>Posts:</strong></td>
                                <td>{{ $user->posts()->count() }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
<script>
    // Preview avatar before upload
    $('#avatar').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#avatar-preview').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        }
    });

    // Handle remove avatar checkbox
    $('#remove_avatar').on('change', function() {
        if ($(this).is(':checked')) {
            $('#avatar').val('');
            $('#avatar-preview').attr('src', 'https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=7F9CF5&background=EBF4FF');
        }
    });
</script>
@endpush
