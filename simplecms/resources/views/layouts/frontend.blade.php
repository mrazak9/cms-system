<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'SimpleCMS'))</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/stisla/modules/bootstrap/css/bootstrap.min.css') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/stisla/modules/fontawesome/css/all.min.css') }}">

    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }

        .navbar {
            padding: 1rem 0;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,.08);
        }

        .nav-link {
            font-weight: 600;
            color: #6c757d;
            transition: color 0.3s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #007bff;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,.15);
            border-radius: 0.5rem;
            margin-top: 0.5rem;
        }

        .dropdown-item {
            padding: 0.5rem 1.5rem;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
            padding-left: 2rem;
        }

        .has-dropdown {
            position: relative;
        }

        .has-dropdown .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1000;
            min-width: 200px;
        }

        .has-dropdown:hover .dropdown-menu {
            display: block;
        }

        main {
            min-height: calc(100vh - 200px);
        }

        footer {
            background-color: #343a40;
            color: #fff;
            padding: 2rem 0;
            margin-top: 3rem;
        }

        footer a {
            color: #adb5bd;
            transition: color 0.3s ease;
        }

        footer a:hover {
            color: #fff;
            text-decoration: none;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'SimpleCMS') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Primary Navigation Menu -->
                {!! render_menu('primary', 'navbar-nav ml-auto', 'nav-item', 'nav-link') !!}

                <!-- Auth Links -->
                <ul class="navbar-nav ml-3">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt mr-1"></i> Login
                            </a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">
                                    <i class="fas fa-user-plus mr-1"></i> Register
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>{{ config('app.name', 'SimpleCMS') }}</h5>
                    <p class="text-muted">A powerful and flexible content management system.</p>
                </div>
                <div class="col-md-3">
                    <h6>Quick Links</h6>
                    {!! render_menu('footer', 'list-unstyled', '', '') !!}
                </div>
                <div class="col-md-3">
                    <h6>Follow Us</h6>
                    <ul class="list-unstyled">
                        <li><a href="#"><i class="fab fa-facebook mr-2"></i> Facebook</a></li>
                        <li><a href="#"><i class="fab fa-twitter mr-2"></i> Twitter</a></li>
                        <li><a href="#"><i class="fab fa-instagram mr-2"></i> Instagram</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4 bg-secondary">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p class="mb-0 text-muted">&copy; {{ date('Y') }} {{ config('app.name', 'SimpleCMS') }}. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('assets/stisla/modules/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/stisla/modules/popper.js') }}"></script>
    <script src="{{ asset('assets/stisla/modules/bootstrap/js/bootstrap.min.js') }}"></script>

    @stack('scripts')
</body>
</html>
