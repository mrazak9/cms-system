<footer class="mt-5">
    <div class="container py-5">
        <div class="row g-4">
            {{-- About Section --}}
            <div class="col-lg-4 col-md-6">
                <h5 class="mb-3">{{ $settings['site_name'] ?? 'SimpleCMS' }}</h5>
                <p class="mb-3">{{ $settings['site_description'] ?? 'A powerful and flexible content management system built with Laravel.' }}</p>

                {{-- Social Media Links --}}
                @if(isset($settings['social_facebook']) || isset($settings['social_twitter']) || isset($settings['social_instagram']) || isset($settings['social_linkedin']))
                    <div class="social-links">
                        @if(isset($settings['social_facebook']))
                            <a href="{{ $settings['social_facebook'] }}" target="_blank" class="me-3" aria-label="Facebook">
                                <i class="fab fa-facebook fa-lg"></i>
                            </a>
                        @endif
                        @if(isset($settings['social_twitter']))
                            <a href="{{ $settings['social_twitter'] }}" target="_blank" class="me-3" aria-label="Twitter">
                                <i class="fab fa-twitter fa-lg"></i>
                            </a>
                        @endif
                        @if(isset($settings['social_instagram']))
                            <a href="{{ $settings['social_instagram'] }}" target="_blank" class="me-3" aria-label="Instagram">
                                <i class="fab fa-instagram fa-lg"></i>
                            </a>
                        @endif
                        @if(isset($settings['social_linkedin']))
                            <a href="{{ $settings['social_linkedin'] }}" target="_blank" class="me-3" aria-label="LinkedIn">
                                <i class="fab fa-linkedin fa-lg"></i>
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Quick Links / Footer Menu --}}
            <div class="col-lg-2 col-md-6">
                <h5 class="mb-3">Quick Links</h5>
                <ul class="list-unstyled">
                    @if(isset($footerMenu) && $footerMenu && $footerMenu->menuItems->count() > 0)
                        @foreach($footerMenu->menuItems as $item)
                            <li class="mb-2">
                                <a href="{{ $item->getUrl() }}" @if($item->target) target="{{ $item->target }}" @endif>
                                    {{ $item->title }}
                                </a>
                            </li>
                        @endforeach
                    @else
                        {{-- Default links if no footer menu --}}
                        <li class="mb-2"><a href="{{ url('/') }}">Home</a></li>
                        <li class="mb-2"><a href="{{ route('blog.index') }}">Blog</a></li>
                    @endif
                </ul>
            </div>

            {{-- Categories --}}
            <div class="col-lg-3 col-md-6">
                <h5 class="mb-3">Categories</h5>
                <ul class="list-unstyled">
                    @php
                        $footerCategories = \App\Models\Category::withCount(['posts' => function ($query) {
                            $query->published();
                        }])
                        ->having('posts_count', '>', 0)
                        ->take(5)
                        ->get();
                    @endphp
                    @if($footerCategories->count() > 0)
                        @foreach($footerCategories as $category)
                            <li class="mb-2">
                                <a href="{{ route('blog.category', $category->slug) }}">{{ $category->name }}</a>
                            </li>
                        @endforeach
                    @else
                        <li class="mb-2 text-muted">No categories available</li>
                    @endif
                </ul>
            </div>

            {{-- Contact Info --}}
            <div class="col-lg-3 col-md-6">
                <h5 class="mb-3">Contact Info</h5>
                <ul class="list-unstyled">
                    @if(isset($settings['contact_email']))
                        <li class="mb-2">
                            <i class="fas fa-envelope me-2"></i>
                            <a href="mailto:{{ $settings['contact_email'] }}">{{ $settings['contact_email'] }}</a>
                        </li>
                    @endif
                    @if(isset($settings['contact_phone']))
                        <li class="mb-2">
                            <i class="fas fa-phone me-2"></i>
                            <a href="tel:{{ $settings['contact_phone'] }}">{{ $settings['contact_phone'] }}</a>
                        </li>
                    @endif
                    @if(isset($settings['contact_address']))
                        <li class="mb-2">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            {{ $settings['contact_address'] }}
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <hr class="my-4 bg-light">

        {{-- Copyright --}}
        <div class="row">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <p class="mb-0">
                    &copy; {{ date('Y') }} {{ $settings['site_name'] ?? 'SimpleCMS' }}. All rights reserved.
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="mb-0">
                    Powered by <a href="https://laravel.com" target="_blank" class="text-white">Laravel</a> & SimpleCMS
                </p>
            </div>
        </div>
    </div>
</footer>
