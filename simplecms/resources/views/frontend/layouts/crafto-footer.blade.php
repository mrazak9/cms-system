{{-- start footer --}}
<footer class="p-0 fs-16 border-top border-color-extra-medium-gray">
    <div class="container">
        <div class="row justify-content-center pt-6 sm-pt-40px">
            {{-- start footer column - Logo & Social --}}
            <div class="col-6 col-xl-3 col-lg-12 col-sm-6 last-paragraph-no-margin text-xl-start text-lg-center order-sm-1 lg-mb-50px sm-mb-30px">
                <a href="{{ url('/') }}" class="footer-logo mb-15px d-inline-block">
                    @if(isset($settings['site_logo']) && $settings['site_logo'])
                        <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="{{ $settings['site_name'] ?? 'SimpleCMS' }}">
                    @else
                        <img src="{{ asset('crafto/images/demo-corporate-logo-black.png') }}" data-at2x="{{ asset('crafto/images/demo-corporate-logo-black@2x.png') }}" alt="{{ $settings['site_name'] ?? 'SimpleCMS' }}">
                    @endif
                </a>
                <p class="lh-30 w-90 xl-w-100 mx-lg-auto mx-xl-0">
                    {{ $settings['site_description'] ?? 'A powerful and flexible content management system' }}
                </p>
                @if(isset($settings['social_facebook']) || isset($settings['social_twitter']) || isset($settings['social_instagram']) || isset($settings['social_linkedin']))
                    <div class="elements-social social-icon-style-02 mt-20px xs-mt-15px">
                        <ul class="medium-icon dark">
                            @if(isset($settings['social_facebook']))
                                <li class="my-0"><a class="facebook" href="{{ $settings['social_facebook'] }}" target="_blank"><i class="fa-brands fa-facebook-f"></i></a></li>
                            @endif
                            @if(isset($settings['social_twitter']))
                                <li class="my-0"><a class="twitter" href="{{ $settings['social_twitter'] }}" target="_blank"><i class="fa-brands fa-twitter"></i></a></li>
                            @endif
                            @if(isset($settings['social_instagram']))
                                <li class="my-0"><a class="instagram" href="{{ $settings['social_instagram'] }}" target="_blank"><i class="fa-brands fa-instagram"></i></a></li>
                            @endif
                            @if(isset($settings['social_linkedin']))
                                <li class="my-0"><a class="dribbble" href="{{ $settings['social_linkedin'] }}" target="_blank"><i class="fa-brands fa-linkedin"></i></a></li>
                            @endif
                        </ul>
                    </div>
                @endif
            </div>
            {{-- end footer column --}}

            {{-- start footer column - Footer Menu --}}
            @if(isset($footerMenu) && $footerMenu && $footerMenu->menuItems->count() > 0)
                <div class="col-6 col-xl-2 col-lg-3 col-sm-4 xs-mb-30px order-sm-3 order-lg-2">
                    <span class="fs-17 fw-600 d-block text-dark-gray mb-5px">Quick Links</span>
                    <ul>
                        @foreach($footerMenu->menuItems->take(5) as $item)
                            <li><a href="{{ $item->getUrl() }}" @if($item->target) target="{{ $item->target }}" @endif>{{ $item->title }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="col-6 col-xl-2 col-lg-3 col-sm-4 xs-mb-30px order-sm-3 order-lg-2">
                    <span class="fs-17 fw-600 d-block text-dark-gray mb-5px">Company</span>
                    <ul>
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><a href="{{ route('blog.index') }}">Blog</a></li>
                    </ul>
                </div>
            @endif
            {{-- end footer column --}}

            {{-- start footer column - Categories --}}
            @php
                $footerCategories = \App\Models\Category::withCount(['posts' => function ($query) {
                    $query->published();
                }])
                ->having('posts_count', '>', 0)
                ->take(5)
                ->get();
            @endphp
            @if($footerCategories->count() > 0)
                <div class="col-6 col-xl-2 col-lg-3 col-sm-4 xs-mb-30px order-sm-4 order-lg-3">
                    <span class="fs-17 fw-600 d-block text-dark-gray mb-5px">Categories</span>
                    <ul>
                        @foreach($footerCategories as $category)
                            <li><a href="{{ route('blog.category', $category->slug) }}">{{ $category->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endif
            {{-- end footer column --}}

            {{-- start footer column - Contact Info --}}
            @if(isset($settings['contact_email']) || isset($settings['contact_phone']) || isset($settings['contact_address']))
                <div class="col-6 col-xl-2 col-lg-3 col-sm-4 xs-mb-30px order-sm-5 order-lg-4">
                    <span class="fs-17 fw-600 d-block text-dark-gray mb-5px">Contact</span>
                    <ul>
                        @if(isset($settings['contact_email']))
                            <li><a href="mailto:{{ $settings['contact_email'] }}">{{ $settings['contact_email'] }}</a></li>
                        @endif
                        @if(isset($settings['contact_phone']))
                            <li><a href="tel:{{ $settings['contact_phone'] }}">{{ $settings['contact_phone'] }}</a></li>
                        @endif
                        @if(isset($settings['contact_address']))
                            <li class="text-muted">{{ Str::limit($settings['contact_address'], 50) }}</li>
                        @endif
                    </ul>
                </div>
            @endif
            {{-- end footer column --}}

            {{-- start footer column - Newsletter --}}
            <div class="col-xl-3 col-lg-3 col-sm-6 md-mb-50px sm-mb-30px xs-mb-0 order-sm-2 order-lg-5">
                <span class="fs-17 fw-600 d-block text-dark-gray mb-5px">Subscribe newsletter</span>
                <p class="lh-30 w-95 sm-w-100 mb-15px">Subscribe our newsletter to get the latest news and updates!</p>
                <div class="d-inline-block w-100 newsletter-style-02 position-relative">
                    <form action="#" method="post" class="position-relative">
                        @csrf
                        <input class="border-color-extra-medium-gray bg-transparent border-radius-4px w-100 form-control input-small pe-50px required" type="email" name="email" placeholder="Enter your email" />
                        <button class="btn pe-20px submit lh-16" type="submit" aria-label="submit"><i class="feather icon-feather-mail icon-small text-dark-gray"></i></button>
                        <div class="form-results border-radius-4px pt-5px pb-5px ps-15px pe-15px fs-14 lh-22 mt-10px w-100 text-center position-absolute d-none"></div>
                    </form>
                </div>
            </div>
            {{-- end footer column --}}
        </div>

        {{-- Copyright & Footer Menu --}}
        <div class="row justify-content-center align-items-center pt-2">
            {{-- start divider --}}
            <div class="col-12">
                <div class="divider-style-03 divider-style-03-01 border-color-transparent-white-light"></div>
            </div>
            {{-- end divider --}}

            {{-- start copyright --}}
            <div class="col-lg-5 pt-35px pb-35px md-pt-0 order-2 order-lg-1 text-center text-lg-start last-paragraph-no-margin">
                <p>&copy; {{ date('Y') }} {{ $settings['site_name'] ?? 'SimpleCMS' }}. Powered by <a href="https://laravel.com" target="_blank" class="text-dark-gray fw-600 text-decoration-line-bottom">Laravel</a></p>
            </div>
            {{-- end copyright --}}

            {{-- start footer menu --}}
            <div class="col-lg-7 pt-35px pb-35px md-pt-25px md-pb-5px order-1 order-lg-2 text-center text-lg-end">
                <ul class="footer-navbar sm-lh-normal">
                    @if(isset($footerMenu) && $footerMenu && $footerMenu->menuItems->count() > 5)
                        @foreach($footerMenu->menuItems->skip(5)->take(3) as $item)
                            <li><a href="{{ $item->getUrl() }}" class="nav-link" @if($item->target) target="{{ $item->target }}" @endif>{{ $item->title }}</a></li>
                        @endforeach
                    @else
                        <li><a href="#" class="nav-link">Privacy policy</a></li>
                        <li><a href="#" class="nav-link">Terms and conditions</a></li>
                    @endif
                </ul>
            </div>
            {{-- end footer menu --}}
        </div>
    </div>
</footer>
{{-- end footer --}}
