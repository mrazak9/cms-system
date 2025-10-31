<!doctype html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    {{-- Title --}}
    <title>@yield('title', $settings['site_name'] ?? 'SimpleCMS')</title>

    {{-- Meta Tags --}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="{{ $settings['site_name'] ?? 'SimpleCMS' }}">

    {{-- SEO Meta Tags --}}
    <meta name="description" content="@yield('meta_description', $settings['site_description'] ?? '')">
    <meta name="keywords" content="@yield('meta_keywords', '')">

    {{-- Open Graph Meta Tags --}}
    <meta property="og:title" content="@yield('og_title', $settings['site_name'] ?? 'SimpleCMS')">
    <meta property="og:description" content="@yield('og_description', $settings['site_description'] ?? '')">
    <meta property="og:image" content="@yield('og_image', asset('crafto/images/default-og-image.jpg'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    {{-- Twitter Card Meta Tags --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', $settings['site_name'] ?? 'SimpleCMS')">
    <meta name="twitter:description" content="@yield('twitter_description', $settings['site_description'] ?? '')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('crafto/images/default-og-image.jpg'))">

    {{-- Favicon --}}
    @php
        $faviconPath = $settings['site_favicon'] ?? '';
        if ($faviconPath) {
            $faviconUrl = str_starts_with($faviconPath, '/') ? asset($faviconPath) : asset('storage/' . $faviconPath);
        } else {
            $faviconUrl = asset('crafto/images/favicon.png');
        }
    @endphp
    <link rel="shortcut icon" href="{{ $faviconUrl }}">
    <link rel="apple-touch-icon" href="{{ $faviconUrl }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ $faviconUrl }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ $faviconUrl }}">

    {{-- Google Fonts Preconnect --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    {{-- Crafto Template Stylesheets --}}
    <link rel="stylesheet" href="{{ asset('crafto/css/vendors.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('crafto/css/icon.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('crafto/css/style.css') }}"/>
    <link rel="stylesheet" href="{{ asset('crafto/css/responsive.css') }}"/>

    {{-- Additional Page Styles --}}
    @stack('styles')
</head>

<body data-mobile-nav-style="modern" data-mobile-nav-bg-color="#242E45" @yield('body_attributes')>
    <div class="box-layout">
        {{-- Header --}}
        @include('frontend.layouts.crafto-header')

        {{-- Main Content --}}
        <main id="main-content">
            @yield('content')
        </main>

        {{-- Footer --}}
        @include('frontend.layouts.crafto-footer')

        {{-- Scroll Progress --}}
        <div class="scroll-progress d-none d-xxl-block">
            <a href="#" class="scroll-top" aria-label="scroll">
                <span class="scroll-text">Scroll</span>
                <span class="scroll-line"><span class="scroll-point"></span></span>
            </a>
        </div>
    </div>

    {{-- JavaScript Libraries --}}
    <script type="text/javascript" src="{{ asset('crafto/js/jquery.js') }}"></script>
    <script type="text/javascript" src="{{ asset('crafto/js/vendors.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('crafto/js/main.js') }}"></script>

    {{-- Additional Page Scripts --}}
    @stack('scripts')
</body>
</html>
