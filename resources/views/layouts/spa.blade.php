<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name=apple-mobile-web-app-capable content=yes>
    <meta content="text/html; charset=utf-8" http-equiv="content-type" />

    <title>{{ config('app.name', 'MunkiReport') }} ALPHA</title>

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/favicons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicons/favicon-32x32.png') }}" sizes="32x32">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicons/favicon-16x16.png') }}" sizes="16x16">
    <link rel="manifest" href="{{ asset('assets/images/favicons/manifest.json') }}">
    <link rel="mask-icon" href="{{ asset('assets/images/favicons/safari-pinned-tab.svg') }}" color="#5d5858">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicons/favicon.ico') }}">
    <meta name="msapplication-config" content="{{ asset('assets/images/favicons/browserconfig.xml') }}">
    <meta name="theme-color" content="#5d5858">

    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/themes/' . session('theme', 'Default') . '/bootstrap.min.css') }}" id="bootstrap-stylesheet" />

    <!-- munkireport.custom_css -->
    @if (config('_munkireport.custom_css'))
        <link rel="stylesheet" href="{{ config('_munkireport.custom_css') }}" />
    @endif

    <!-- $stylesheets -->
    @isset($stylesheets)
        @foreach ($stylesheets as $stylesheet)
            <link rel="stylesheet" href="{{ asset('assets/css/' . $stylesheet) }}" />
        @endforeach
    @endisset
    @stack('stylesheets')

    <!-- Head scripts -->
    @stack('head_scripts')
</head>

<body class="mr-spa-layout" style="padding-top: 56px;">
@auth
@php
$modules = getMrModuleObj()->loadInfo();
$dashboard = getDashboard()->loadAll();
$page = url()->current();
@endphp
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top"> <!-- bs-docs-nav -->
    <a class="navbar-brand" href="{{ url('') }}">{{ config('app.name', 'MunkiReport') }}</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse bs-navbar-collapse" id="navbarSupportedContent">
        <div class="navbar-nav mr-auto">
            @if($dashboard->getCount() === 1)
                <li {{ Route::is('/') ? 'class="nav-item active"' : 'class="nav-item"' }}>
                    <a class="nav-link" href="{{ url('/') }}">
                        <i class="fa fa-th-large"></i>
                        <span class="visible-lg-inline" data-i18n="nav.main.dashboard"></span>
                    </a>
                </li>
            @else
                <li class="nav-item dropdown {{ Route::is('/show/dashboard') ? " active" : "" }}">
                    <a class="nav-link dropdown-toggle" href="#" role="button" id="dashboardsMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-th-large"></i>
                        <span data-i18n="nav.main.dashboard_plural"></span>
                        <b class="caret"></b>
                    </a>
                    <div class="dashboard dropdown-menu" aria-labelledby="dashboardsMenuLink">
                    @foreach($dashboard->getDropdownData('show/dashboard', $page) as $item)
                        <a class="dropdown-item {{ $item->class }}" href="{{ $item->url }}">
                            <span class="pull-right">{{ strtoupper($item->hotkey) }}</span>
                            <span class="dropdown-link-text ">{{ $item->display_name }}</span>
                        </a>
                    @endforeach
                    </div>
                </li>
                @endif

                <li class="nav-item dropdown {{ Route::is('/show/reports') ? " active" : "" }}">
                    <a class="nav-link dropdown-toggle" href="#" role="button" id="reportsMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-bar-chart-o"></i>
                        <span data-i18n="nav.main.reports"></span>
                        <b class="caret"></b>
                    </a>
                    <div class="report dropdown-menu" aria-labelledby="dashboardsMenuLink">
                    @foreach($modules->getDropdownData('reports', 'show/report', $page) as $item)
                        <a class="dropdown-item {{ $item->class }}" href="{{ $item->url }}" data-i18n="{{ $item->i18n }}"></a>
                    @endforeach
                    </div>
                </li>

                <li class="nav-item dropdown {{ Route::is('/show/listing') ? " active" : "" }}">
                    <a class="nav-link dropdown-toggle" href="#" role="button" id="listingMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-list-alt"></i>
                        <span data-i18n="nav.main.listings"></span>
                        <b class="caret"></b>
                    </a>
                    <div class="listing dropdown-menu" aria-labelledby="listingMenuLink">
                    @foreach($modules->getDropdownData('listings', 'show/listing', $page) as $item)
                        <a class="dropdown-item {{ $item->class }}" href="{{ $item->url }}" data-i18n="{{ $item->i18n }}"></a>
                    @endforeach
                    </div>
                </li>

                @if (Gate::allows('global'))
                <li class="nav-item dropdown {{ Route::is('/admin/show') ? " active" : "" }}">
                    <a class="nav-link dropdown-toggle" href="#" role="button" id="adminMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-list-alt"></i>
                        <span data-i18n="nav.main.admin"></span>
                        <b class="caret"></b>
                    </a>
                    <div class="admin dropdown-menu" aria-labelledby="adminMenuLink">
                        @foreach(scandir(conf('view_path') . 'admin') as $list_url)
                            @if(strpos($list_url, 'php'))
                            @php $page_url = strtok($list_url, '.'); @endphp
                                <a class="dropdown-item {{ Route::is($page_url) ? " active" : "" }}" href="{{ url($page_url) }}" data-i18n="nav.admin.{{ strtok($list_url, '.') }}"></a>
                            @endif
                        @endforeach
                        @foreach($modules->getDropdownData('admin_pages', 'module', $page) as $item)
                            <a class="dropdown-item {{ $item->class }}" href="{{ $item->url }}" data-i18n="{{ $item->i18n }}"></a>
                        @endforeach
                    </div>
                </li>
                @endif
            </div><!-- div navbar-nav mr-auto (left aligned) -->

            <!-- navbar-right -->
            <div class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="#" id="filter-popup" class="nav-link filter-popup">
                        <i class="fa fa-filter"></i>
                    </a>
                </li>

                <li class="dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" id="themeMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-wrench"></i>
                    </a>
                    <div class="theme dropdown-menu" aria-labelledby="themeMenuLink">
                    @foreach(scandir(PUBLIC_ROOT.'assets/themes') AS $theme)
                        @if( $theme != 'fonts' && strpos($theme, '.') === false)
                            <a class="dropdown-item" data-switch="{{ $theme }}" href="#">{{ $theme }}</a>
                        @endif
                    @endforeach
                    </div>
                </li>


                <li class="dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" id="localeMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-globe"></i>
                    </a>
                    <div class="locale dropdown-menu" aria-labelledby="localeMenuLink">
                    @foreach(scandir(PUBLIC_ROOT.'assets/locales') AS $list_url)
                        @if( strpos($list_url, 'json'))
                            @php $lang = strtok($list_url, '.'); @endphp
                            <a class="dropdown-item" href="{{ mr_url($page, false, ['setLng' => $lang]) }}" data-i18n="nav.lang.<?php echo $lang; ?>"><?php echo $lang; ?></a>
                        @endif
                    @endforeach
                    </div>
                </li>

                <li class="dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" id="userMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-user"></i> {{ Auth::user()['email'] }}
                        <b class="caret"></b>
                    </a>

                    <div class="dropdown-menu" aria-labelledby="userMenuLink">
                        <a class="dropdown-item" href="{{ url('/me/tokens') }}" data-i18n="nav.user.tokens">My API Tokens</a>
                        <div class="dropdown-divider"></div>

                        <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="fa fa-power-off"></i>
                            <span data-i18n="nav.user.logout"></span>
                        </button>
                        </form>

                    </div>
                </li>

                @if(config('_munkireport.show_help'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ config('_munkireport.help_url') }}" target="_blank">
                        <i class="fa fa-question"></i>
                    </a>
                </li>
                @endif
            </div><!-- div navbar-nav ml-auto (right aligned) -->
        </div><!-- navbar-collapse -->
</nav>
@endauth

@yield('content')

<!-- original foot partial -->

@auth
<div class="container-fluid">
    <div style="text-align: right; margin: 10px; color: #bbb; font-size: 80%;">
        <i>MunkiReport <span data-i18n="version">Version</span> {{ $GLOBALS['version'] }}</i>
    </div>
</div>
@endauth


{{--<script src="{{ asset('assets/js/munkireport.settings.js') }}"></script>--}}
{{--<script src="{{ asset('assets/js/munkireport.js') }}"></script>--}}

@stack('scripts')
</body>
</html>
