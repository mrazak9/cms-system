<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Dynamic Meta Tags --}}
    <title>@yield('title', $settings['site_name'] ?? 'SimpleCMS')</title>
    <meta name="description" content="@yield('meta_description', $settings['site_description'] ?? '')">
    <meta name="keywords" content="@yield('meta_keywords', '')">
    <meta name="author" content="{{ $settings['site_name'] ?? 'SimpleCMS' }}">

    {{-- Open Graph Meta Tags --}}
    <meta property="og:title" content="@yield('og_title', $settings['site_name'] ?? 'SimpleCMS')">
    <meta property="og:description" content="@yield('og_description', $settings['site_description'] ?? '')">
    <meta property="og:image" content="@yield('og_image', asset('images/default-og-image.jpg'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    {{-- Twitter Card Meta Tags --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', $settings['site_name'] ?? 'SimpleCMS')">
    <meta name="twitter:description" content="@yield('twitter_description', $settings['site_description'] ?? '')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('images/default-og-image.jpg'))">

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    {{-- Font Awesome 6 --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- Custom Styles --}}
    <style>
        :root {
            --primary-color: {{ $settings['primary_color'] ?? '#3182ce' }};
            --secondary-color: {{ $settings['secondary_color'] ?? '#2d3748' }};
            --text-color: #333333;
            --light-bg: #f8f9fa;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: var(--text-color);
            line-height: 1.6;
        }

        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-color) !important;
        }

        .nav-link {
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        footer {
            background-color: var(--secondary-color);
            color: #ffffff;
        }

        footer a {
            color: #ffffff;
            text-decoration: none;
            transition: opacity 0.3s ease;
        }

        footer a:hover {
            opacity: 0.8;
        }

        .section {
            padding: 60px 0;
        }

        @media (max-width: 768px) {
            .section {
                padding: 40px 0;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    {{-- Header --}}
    @include('frontend.layouts.header')

    {{-- Main Content --}}
    <main id="main-content">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('frontend.layouts.footer')

    {{-- Bootstrap 5 JS Bundle --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    {{-- Custom Scripts --}}
    @stack('scripts')
</body>
</html>
