{{--

MunkiReport Blank Layout

This layout should be used for unauthenticated sessions (eg for login pages and error pages with no navigation) that need to retain backwards compatibility with the
MunkiReport v5 frontend stack (Bootstrap/jQuery/Datatables.NET)

--}}<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name=apple-mobile-web-app-capable content=yes>
    <meta content="text/html; charset=utf-8" http-equiv="content-type" />

    <title>{{ config('app.name', 'MunkiReport') }}</title>

    <!-- Styles -->
    @if (config('frontend.css.use_cdn', false))
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.13.4/b-2.3.6/b-html5-2.3.6/b-print-2.3.6/datatables.min.css"/>
    @else
        <!-- bootstrap.min.js is loaded locally using the `Default` theme -->
        <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/css/buttons.bootstrap4.min.css') }}" />
    @endif

    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/nvd3/nv.d3.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/themes/Default/bootstrap.min.css') }}" id="bootstrap-stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/themes/Default/nvd3.override.css') }}" id="nvd3-override-stylesheet" />
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
<body class="mr-blank-layout" style="padding-top: 56px;">
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name', 'MunkiReport') }}</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse bs-navbar-collapse" id="navbarSupportedContent">
        <div class="navbar-nav mr-auto">
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
