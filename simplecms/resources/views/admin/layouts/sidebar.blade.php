<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        @php
            $siteName = \App\Models\Setting::get('site_name', 'SimpleCMS');
            $siteLogo = \App\Models\Setting::get('site_logo');
            if ($siteLogo) {
                $logoUrl = str_starts_with($siteLogo, '/') ? asset($siteLogo) : asset('storage/' . $siteLogo);
            } else {
                $logoUrl = null;
            }
        @endphp
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $siteName }}" style="max-height: 32px; max-width: 150px; object-fit: contain;">
                @else
                    <i class="fas fa-layer-group"></i> {{ $siteName }}
                @endif
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('admin.dashboard') }}">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $siteName }}" style="max-height: 28px; max-width: 40px; object-fit: contain;">
                @else
                    {{ strtoupper(substr($siteName, 0, 2)) }}
                @endif
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-fire"></i> <span>Dashboard</span>
                </a>
            </li>

            <li class="menu-header">Content</li>
            @can('pages.view')
            <li class="{{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.pages.index') }}">
                    <i class="far fa-file-alt"></i> <span>Pages</span>
                </a>
            </li>
            @endcan

            @if(auth()->user()->can('posts.view') || auth()->user()->can('categories.view'))
            <li class="dropdown {{ request()->routeIs('admin.posts.*') || request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <i class="fas fa-newspaper"></i> <span>Posts & Categories</span>
                </a>
                <ul class="dropdown-menu">
                    @can('posts.view')
                    <li class="{{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.posts.index') }}">All Posts</a>
                    </li>
                    @endcan
                    @can('categories.view')
                    <li class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.categories.index') }}">Categories</a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endif

            @can('menus.view')
            <li class="{{ request()->routeIs('admin.menus.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.menus.index') }}">
                    <i class="fas fa-bars"></i> <span>Menus</span>
                </a>
            </li>
            @endcan

            <li class="menu-header">Appearance</li>
            @can('themes.view')
            <li class="{{ request()->routeIs('admin.themes.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.themes.index') }}">
                    <i class="fas fa-paint-brush"></i> <span>Themes</span>
                </a>
            </li>
            @endcan

            @can('media.view')
            <li class="{{ request()->routeIs('admin.media.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.media.index') }}">
                    <i class="far fa-images"></i> <span>Media Library</span>
                </a>
            </li>
            @endcan

            <li class="menu-header">System</li>
            @can('users.view')
            <li class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.users.index') }}">
                    <i class="fas fa-users"></i> <span>Users</span>
                </a>
            </li>
            @endcan

            @can('roles.view')
            <li class="{{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.roles.index') }}">
                    <i class="fas fa-user-shield"></i> <span>Roles & Permissions</span>
                </a>
            </li>
            @endcan

            @can('settings.view')
            <li class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.settings.index') }}">
                    <i class="fas fa-cog"></i> <span>Settings</span>
                </a>
            </li>
            @endcan
        </ul>

        <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
            <a href="{{ url('/') }}" target="_blank" class="btn btn-primary btn-lg btn-block btn-icon-split">
                <i class="fas fa-external-link-alt"></i> Visit Site
            </a>
        </div>
    </aside>
</div>
