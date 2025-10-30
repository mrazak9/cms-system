<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
        <div class="container">
            {{-- Logo/Brand --}}
            <a class="navbar-brand" href="{{ url('/') }}">
                @if(isset($settings['site_logo']) && $settings['site_logo'])
                    <img src="{{ asset($settings['site_logo']) }}" alt="{{ $settings['site_name'] ?? 'SimpleCMS' }}" height="40">
                @else
                    {{ $settings['site_name'] ?? 'SimpleCMS' }}
                @endif
            </a>

            {{-- Mobile Toggle Button --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            {{-- Navigation Menu --}}
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    @if(isset($primaryMenu) && $primaryMenu)
                        @foreach($primaryMenu->menuItems as $item)
                            @if($item->children->count() > 0)
                                {{-- Dropdown Menu --}}
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown{{ $item->id }}" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ $item->title }}
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown{{ $item->id }}">
                                        @foreach($item->children as $child)
                                            <li>
                                                <a class="dropdown-item" href="{{ $child->getUrl() }}" @if($child->target) target="{{ $child->target }}" @endif>
                                                    {{ $child->title }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                {{-- Regular Menu Item --}}
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is($item->url) ? 'active' : '' }}" href="{{ $item->getUrl() }}" @if($item->target) target="{{ $item->target }}" @endif>
                                        {{ $item->title }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    @else
                        {{-- Default Menu Items --}}
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('blog*') ? 'active' : '' }}" href="{{ route('blog.index') }}">Blog</a>
                        </li>
                    @endif

                    {{-- Search Form (Optional) --}}
                    <li class="nav-item ms-lg-3">
                        <form action="{{ route('search') }}" method="GET" class="d-flex">
                            <div class="input-group input-group-sm">
                                <input type="search" name="q" class="form-control" placeholder="Search..." aria-label="Search" value="{{ request('q') }}">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
