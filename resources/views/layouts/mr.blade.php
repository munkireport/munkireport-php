<!doctype html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name=apple-mobile-web-app-capable content=yes>
    <meta content="text/html; charset=utf-8" http-equiv="content-type" />

    <title>{{ config('app.name', 'MunkiReport') }}</title>

    <!-- Scripts -->

    <!-- Styles -->
    <!-- Temporary for Bootstrap 4.6 upgrade: Disable normal styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">


    {{--    <link rel="stylesheet" href="{{ asset('assets/themes/' . sess_get('theme', 'Default') . '/bootstrap.min.css') }}" id="bootstrap-stylesheet" />--}}
{{--    <link rel="stylesheet" href="{{ asset('assets/nvd3/nv.d3.min.css') }}" />--}}
{{--    <link rel="stylesheet" href="{{ asset('assets/themes/' . sess_get('theme', 'Default') . '/nvd3.override.css') }}" id="nvd3-override-stylesheet" />--}}
{{--    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />--}}
{{--    <link rel="stylesheet" media="screen" href="{{ asset('assets/css/datatables.min.css') }}" />--}}
    <link href="{{ asset('assets/css/font-awesome.min.css') }}" rel="stylesheet">

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

    <!-- $stylesheets -->
    @isset($stylesheets)
        @foreach ($stylesheets as $stylesheet)
            <link rel="stylesheet" href="{{ asset('assets/css/' . $stylesheet) }}" />
        @endforeach
    @endisset
    @stack('stylesheets')

    <script>
      var baseUrl = "{{ conf('subdirectory') }}",
        appUrl = "{{ url('/') }}",
        default_theme = "{{ config('_munkireport.default_theme') }}",
        businessUnitsEnabled = {{ config('_munkireport.enable_business_units') ? 'true' : 'false' }};
      isAdmin = true;
      isManager = true;
      isArchiver = true;
    </script>

    <!-- temporarily using CDN while testing, do not use the slim version -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>

    <!-- VueJS 2.x -->
    @env(['local', 'development'])
    <!-- development version, includes helpful console warnings -->
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    @endenv
    @env(['production'])
    <!-- production version, optimized for size and speed -->
    <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
    @endenv
    <script src="https://unpkg.com/vue-router/dist/vue-router.js"></script>

    @isset($scripts)
        @foreach ($scripts as $script)
            <script src="{{ asset('assets/js/' . $script) }}" type="text/javascript"></script>
        @endforeach
    @endisset

    @stack('head_scripts')
</head>

<body class="mr-blade-layout" style="padding-top: 56px;">

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
{{--                        <li class="{{ $item->class }}">--}}
                        <a class="dropdown-item" href="{{ $item->url }}">
                            <span class="pull-right">{{ strtoupper($item->hotkey) }}</span>
                            <span class="dropdown-link-text ">{{ $item->display_name }}</span>
                        </a>
{{--                        </li>--}}

                    @endforeach
                    </div>
                </li>
                @endif

                <li class="nav-item dropdown {{ Route::is('/show/reports') ? " active" : "" }}">
                    <a class="nav-link dropdown-toggle" href="#" role="button" id="reportsMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-th-large"></i>
                        <span data-i18n="nav.main.reports"></span>
                        <b class="caret"></b>
                    </a>
                    <div class="report dropdown-menu" aria-labelledby="dashboardsMenuLink">
                    @foreach($modules->getDropdownData('reports', 'show/report', $page) as $item)
{{--                        <li class="{{ $item->class }}">--}}
                        <a class="dropdown-item" href="{{ $item->url }}" data-i18n="{{ $item->i18n }}"></a>
{{--                        </li>--}}

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
{{--                        <li class="{{ $item->class }}">--}}
                            <a class="dropdown-item" href="{{ $item->url }}" data-i18n="{{ $item->i18n }}"></a>
{{--                        </li>--}}
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
    {{--                        <li class="{{ Route::is($page_url) ? " active" : "" }}">--}}
                                <a class="dropdown-item" href="{{ url($page_url) }}" data-i18n="nav.admin.{{ strtok($list_url, '.') }}"></a>
    {{--                        </li>--}}
                            @endif
                        @endforeach
                        @foreach($modules->getDropdownData('admin_pages', 'module', $page) as $item)
{{--                        <li class="{{ $item->class }}">--}}
                            <a class="dropdown-item" href="{{ $item->url }}" data-i18n="{{ $item->i18n }}"></a>
{{--                        </li>--}}
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
                        <!-- TODO: this must be a form POST -->
                        <a class="dropdown-item" href="{{ url('/logout') }}">
                            <i class="fa fa-power-off"></i>
                            <span data-i18n="nav.user.logout"></span>
                        </a>
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
        </div>

</nav>
@endauth

@yield('content')

<!-- original foot partial -->

@auth

<div class="container">

    <div style="text-align: right; margin: 10px; color: #bbb; font-size: 80%;">

        <i>MunkiReport <span data-i18n="version">Version</span> {{ $GLOBALS['version'] }}</i>

    </div>

</div>

@endauth

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button data-i18n="dialog.cancel" type="button" class="btn btn-default" data-dismiss="modal"></button>
                <button type="button" class="btn btn-primary ok"></button>
            </div>
        </div>
    </div>
</div>

@foreach($GLOBALS['alerts'] AS $type => $list)

<div class="mr-alert alert alert-dismissable alert-<?php echo $type; ?>">

    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

    <ul>

        @foreach($list AS $msg)
        <li>{{ $msg }}</li>
        @endforeach

    </ul>

</div>

@endforeach

<script>
  $('.mr-alert').prependTo('body>div.container:first');
</script>

<!-- Temporary during Bootstrap 4.6 update -->
<!--    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>-->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
<!--    <script src="https://unpkg.com/i18next/dist/umd/i18next.min.js"></script>-->

{{--<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>--}}
<script src="{{ asset('assets/js/datatables.min.js') }}"></script>
<script src="{{ asset('assets/js/moment.min.js') }}"></script>
<!-- i18next, v1.10.2 -->
<script src="{{ asset('assets/js/i18next.min.js') }}"></script>
<script src="{{ asset('assets/js/d3/d3.min.js') }}"></script>
<script src="{{ asset('assets/js/nv.d3.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.hotkeys/jquery.hotkeys.js') }}"></script>
<script src="{{ asset('assets/js/munkireport.settings.js') }}"></script>

<script>
  // Inject debug value from php
  mr.debug = {{ config('app.debug') ? 'true' : 'false' }};
  @php
  $dashboard = getDashboard()->loadAll();
  @endphp

  @foreach($dashboard->getDropdownData('show/dashboard', url()->current()) as $item)
  @if($item->hotkey)
    mr.setHotKey('{{ $item->hotkey }}', appUrl + '/show/dashboard/{{ $item->name }}');
  @endif
  @endforeach
</script>

<!-- munkireport.custom_js -->
@if(config('_munkireport.custom_js'))
<script src="{{ config('_munkireport.custom_js') }}"></script>
@endif

<script src="{{ asset('assets/js/munkireport.js') }}"></script>

@if(isset($recaptcha) && conf('recaptchaloginpublickey'))
<script src='https://www.google.com/recaptcha/api.js' async defer></script>
<script>
  function onSubmit(token) {
    document.getElementById("login-form").submit();
  }
</script>
@endif

<script>
  // Include csrf in all requests
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
</script>
<script>
  $(document).on('appUpdate', function(){
    //$.getJSON( appUrl + '/module/notification/runCheck', function( data ) {
    // Maybe add some counter to only run every 10 mins.
    // CHeck if the data contains errors
    // Check if there are desktop notifications
    //});
  });
</script>

@stack('scripts')
</body>
</html>
