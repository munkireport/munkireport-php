<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name=apple-mobile-web-app-capable content=yes>
    <meta content="text/html; charset=utf-8" http-equiv="content-type" />

    <title>{{ config('app.name', 'MunkiReport') }}</title>

    <!-- Styles -->
    @if (config('frontend.css.use_cdn', false))
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.24/b-1.7.0/b-html5-1.7.0/b-print-1.7.0/datatables.min.css"/>
    @else
    <!-- bootstrap.min.js is loaded locally using the `Default` theme -->
        <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/css/buttons.bootstrap4.min.css') }}" />
    @endif

    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/nvd3/nv.d3.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/themes/' . session('theme', 'Default') . '/bootstrap.min.css') }}" id="bootstrap-stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/themes/' . session('theme', 'Default') . '/nvd3.override.css') }}" id="nvd3-override-stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />

    <!-- Head scripts -->
    @if (config('frontend.javascript.use_cdn', false))
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
    @else
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    @endif

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/favicons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicons/favicon-32x32.png') }}" sizes="32x32">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicons/favicon-16x16.png') }}" sizes="16x16">
    <link rel="manifest" href="{{ asset('assets/images/favicons/manifest.json') }}">
    <link rel="mask-icon" href="{{ asset('assets/images/favicons/safari-pinned-tab.svg') }}" color="#5d5858">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicons/favicon.ico') }}">
    <meta name="msapplication-config" content="{{ asset('assets/images/favicons/browserconfig.xml') }}">
    <meta name="theme-color" content="#5d5858">

    <!-- munkireport.custom_css -->
    @if (config('_munkireport.custom_css'))
        <link rel="stylesheet" href="{{ config('_munkireport.custom_css') }}" />
    @endif

</head>
<body style="padding-top: 56px;">
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name', 'MunkiReport') }}</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse bs-navbar-collapse" id="navbarSupportedContent">
        <div class="navbar-nav mr-auto">
        </div>
        <div class="navbar-nav ml-auto">
            @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
                @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                @endif
            @else
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            @endguest
        </div>
    </div>
</nav>
<div class="container">
    <main class="py-4">
        @yield('content')
    </main>
</div>
</body>
</html>
