<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}">
                <i class="fas fa-layer-group"></i> SimpleCMS
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('admin.dashboard') }}">SC</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-fire"></i> <span>Dashboard</span>
                </a>
            </li>

            <li class="menu-header">Content</li>
            <li class="{{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.pages.index') }}">
                    <i class="far fa-file-alt"></i> <span>Pages</span>
                </a>
            </li>

            <li class="dropdown {{ request()->routeIs('admin.posts.*') || request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <i class="fas fa-newspaper"></i> <span>Posts & Categories</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.posts.index') }}">All Posts</a>
                    </li>
                    <li class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.categories.index') }}">Categories</a>
                    </li>
                </ul>
            </li>

            <li class="{{ request()->routeIs('admin.menus.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.menus.index') }}">
                    <i class="fas fa-bars"></i> <span>Menus</span>
                </a>
            </li>

            <li class="menu-header">Appearance</li>
            <li class="{{ request()->routeIs('admin.themes.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.themes.index') }}">
                    <i class="fas fa-paint-brush"></i> <span>Themes</span>
                </a>
            </li>

            <li class="{{ request()->routeIs('admin.media.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.media.index') }}">
                    <i class="far fa-images"></i> <span>Media Library</span>
                </a>
            </li>

            <li class="menu-header">System</li>
            <li class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.settings.index') }}">
                    <i class="fas fa-cog"></i> <span>Settings</span>
                </a>
            </li>
        </ul>

        <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
            <a href="{{ url('/') }}" target="_blank" class="btn btn-primary btn-lg btn-block btn-icon-split">
                <i class="fas fa-external-link-alt"></i> Visit Site
            </a>
        </div>
    </aside>
</div>
