@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'User Dashboard')

@section('breadcrumb')
    <div class="breadcrumb-item active">Dashboard</div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Welcome, {{ auth()->user()->name }}!</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Your Role:</strong>
                        @foreach(auth()->user()->roles as $role)
                            <span class="badge badge-primary">{{ ucfirst($role->name) }}</span>
                        @endforeach
                    </div>

                    <h5 class="mb-3">Your Capabilities</h5>

                    <div class="row">
                        @can('posts.view')
                        <div class="col-md-4 mb-3">
                            <div class="card card-primary">
                                <div class="card-body text-center">
                                    <i class="fas fa-newspaper fa-3x mb-3"></i>
                                    <h6>Posts</h6>
                                    <p class="text-muted small">Manage blog posts</p>
                                    <a href="{{ route('admin.posts.index') }}" class="btn btn-primary btn-sm">
                                        View Posts
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endcan

                        @can('pages.view')
                        <div class="col-md-4 mb-3">
                            <div class="card card-success">
                                <div class="card-body text-center">
                                    <i class="fas fa-file-alt fa-3x mb-3"></i>
                                    <h6>Pages</h6>
                                    <p class="text-muted small">Manage pages</p>
                                    <a href="{{ route('admin.pages.index') }}" class="btn btn-success btn-sm">
                                        View Pages
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endcan

                        @can('media.view')
                        <div class="col-md-4 mb-3">
                            <div class="card card-info">
                                <div class="card-body text-center">
                                    <i class="fas fa-images fa-3x mb-3"></i>
                                    <h6>Media Library</h6>
                                    <p class="text-muted small">Manage media files</p>
                                    <a href="{{ route('admin.media.index') }}" class="btn btn-info btn-sm">
                                        View Media
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endcan

                        @can('users.view')
                        <div class="col-md-4 mb-3">
                            <div class="card card-warning">
                                <div class="card-body text-center">
                                    <i class="fas fa-users fa-3x mb-3"></i>
                                    <h6>Users</h6>
                                    <p class="text-muted small">Manage users</p>
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-warning btn-sm">
                                        View Users
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endcan

                        @can('roles.view')
                        <div class="col-md-4 mb-3">
                            <div class="card card-danger">
                                <div class="card-body text-center">
                                    <i class="fas fa-user-shield fa-3x mb-3"></i>
                                    <h6>Roles & Permissions</h6>
                                    <p class="text-muted small">Manage roles</p>
                                    <a href="{{ route('admin.roles.index') }}" class="btn btn-danger btn-sm">
                                        View Roles
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endcan

                        @can('settings.view')
                        <div class="col-md-4 mb-3">
                            <div class="card card-secondary">
                                <div class="card-body text-center">
                                    <i class="fas fa-cog fa-3x mb-3"></i>
                                    <h6>Settings</h6>
                                    <p class="text-muted small">System settings</p>
                                    <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary btn-sm">
                                        View Settings
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endcan
                    </div>

                    @if(!auth()->user()->can('posts.view') && !auth()->user()->can('pages.view') && !auth()->user()->can('media.view'))
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            You don't have any special permissions yet. Contact your administrator to assign you a role.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
