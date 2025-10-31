{{-- start header --}}
<header>
    {{-- start navigation --}}
    <nav class="navbar navbar-expand-lg header-light bg-white disable-fixed">
        <div class="container-fluid">
            {{-- Logo --}}
            <div class="col-auto col-xl-3 col-lg-2 me-lg-0 me-auto">
                <a class="navbar-brand" href="{{ url('/') }}">
                    @if(isset($settings['site_logo']) && $settings['site_logo'])
                        @php
                            $logoPath = $settings['site_logo'];
                            $logoUrl = str_starts_with($logoPath, '/') ? asset($logoPath) : asset('storage/' . $logoPath);
                        @endphp
                        <img src="{{ $logoUrl }}" alt="{{ $settings['site_name'] ?? 'SimpleCMS' }}" class="default-logo">
                        <img src="{{ $logoUrl }}" alt="{{ $settings['site_name'] ?? 'SimpleCMS' }}" class="alt-logo">
                        <img src="{{ $logoUrl }}" alt="{{ $settings['site_name'] ?? 'SimpleCMS' }}" class="mobile-logo">
                    @else
                        <img src="{{ asset('crafto/images/demo-corporate-logo-black.png') }}" data-at2x="{{ asset('crafto/images/demo-corporate-logo-black@2x.png') }}" alt="{{ $settings['site_name'] ?? 'SimpleCMS' }}" class="default-logo">
                        <img src="{{ asset('crafto/images/demo-corporate-logo-black.png') }}" data-at2x="{{ asset('crafto/images/demo-corporate-logo-black@2x.png') }}" alt="{{ $settings['site_name'] ?? 'SimpleCMS' }}" class="alt-logo">
                        <img src="{{ asset('crafto/images/demo-corporate-logo-black.png') }}" data-at2x="{{ asset('crafto/images/demo-corporate-logo-black@2x.png') }}" alt="{{ $settings['site_name'] ?? 'SimpleCMS' }}" class="mobile-logo">
                    @endif
                </a>
            </div>

            {{-- Menu --}}
            <div class="col-auto col-xl-6 col-lg-8 menu-order position-static">
                <button class="navbar-toggler float-start" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-label="Toggle navigation">
                    <span class="navbar-toggler-line"></span>
                    <span class="navbar-toggler-line"></span>
                    <span class="navbar-toggler-line"></span>
                    <span class="navbar-toggler-line"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                    <ul class="navbar-nav">
                        @if(isset($primaryMenu) && $primaryMenu && $primaryMenu->menuItems->count() > 0)
                            @foreach($primaryMenu->menuItems as $item)
                                @if($item->children && $item->children->count() > 0)
                                    {{-- Dropdown Menu --}}
                                    <li class="nav-item dropdown dropdown-with-icon-style02">
                                        <a href="{{ $item->getUrl() }}" class="nav-link">{{ $item->title }}</a>
                                        <i class="fa-solid fa-angle-down dropdown-toggle" id="navbarDropdownMenuLink{{ $item->id }}" role="button" data-bs-toggle="dropdown" aria-expanded="false"></i>
                                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink{{ $item->id }}">
                                            @foreach($item->children as $child)
                                                <li>
                                                    <a href="{{ $child->getUrl() }}" @if($child->target) target="{{ $child->target }}" @endif>
                                                        {{ $child->title }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @else
                                    {{-- Regular Menu Item --}}
                                    <li class="nav-item">
                                        <a href="{{ $item->getUrl() }}" class="nav-link {{ request()->is(trim($item->url, '/')) ? 'active' : '' }}" @if($item->target) target="{{ $item->target }}" @endif>
                                            {{ $item->title }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        @else
                            {{-- Default Menu Items --}}
                            <li class="nav-item"><a href="{{ url('/') }}" class="nav-link">Home</a></li>
                            <li class="nav-item"><a href="{{ route('blog.index') }}" class="nav-link">Blog</a></li>
                        @endif
                    </ul>
                </div>
            </div>

            {{-- Header Actions --}}
            <div class="col-auto col-xl-3 col-lg-2 text-end md-pe-0">
                <div class="header-icon">
                    {{-- Search Icon --}}
                    <div class="header-search-icon icon">
                        <a href="#" class="search-form-icon header-search-form"><i class="feather icon-feather-search"></i></a>
                        {{-- start search input --}}
                        <div class="search-form-wrapper">
                            <button title="Close" type="button" class="search-close">×</button>
                            <form id="search-form" role="search" method="get" class="search-form text-left" action="{{ route('search') }}">
                                <div class="search-form-box">
                                    <h2 class="text-dark-gray text-center fw-600 mb-4 ls-minus-1px">What are you looking for?</h2>
                                    <input class="search-input" placeholder="Enter your keywords..." name="q" value="{{ request('q') }}" type="text" autocomplete="off">
                                    <button type="submit" class="search-button">
                                        <i class="feather icon-feather-search" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                        {{-- end search input --}}
                    </div>

                    {{-- Auth Button --}}
                    <div class="header-button ms-20px d-none d-xl-inline-block">
                        @auth
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-rounded btn-transparent-light-gray border-1 btn-medium btn-switch-text text-transform-none">
                                <span>
                                    <span class="btn-double-text fw-600" data-text="Dashboard">Dashboard</span>
                                    <span><i class="fa-regular fa-user"></i></span>
                                </span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-rounded btn-transparent-light-gray border-1 btn-medium btn-switch-text text-transform-none">
                                <span>
                                    <span class="btn-double-text fw-600" data-text="Login">Login</span>
                                    <span><i class="fa-regular fa-user"></i></span>
                                </span>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </nav>
    {{-- end navigation --}}
</header>
{{-- end header --}}
