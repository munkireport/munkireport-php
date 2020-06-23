<!doctype html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name=apple-mobile-web-app-capable content=yes>
    <meta content="text/html; charset=utf-8" http-equiv="content-type" />

    <title>{{ config('app.name', 'MunkiReport') }}</title>

    <!-- Scripts -->

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('assets/themes/' . sess_get('theme', 'Default') . '/bootstrap.min.css') }}" id="bootstrap-stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/nvd3/nv.d3.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/themes/' . sess_get('theme', 'Default') . '/nvd3.override.css') }}" id="nvd3-override-stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <link rel="stylesheet" media="screen" href="{{ asset('assets/css/datatables.min.css') }}" />
    <link href="{{ asset('assets/css/font-awesome.min.css') }}" rel="stylesheet">
    @if (config('munkireport.custom_css'))
    <link rel="stylesheet" href="{{ config('munkireport.custom_css') }}" />
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

    @isset($stylesheets)
        @foreach ($stylesheets as $stylesheet)
            <link rel="stylesheet" href="{{ asset('assets/css/' . $stylesheet) }}" />
        @endforeach
    @endisset

    <script>
      var baseUrl = "{{ conf('subdirectory') }}",
        appUrl = "{{ url('/') }}",
        default_theme = "{{ config('munkireport.default_theme') }}",
        businessUnitsEnabled = {{ config('munkireport.enable_business_units') ? 'true' : 'false' }};
      isAdmin = {{ auth('admin') ? 'true' : 'false' }};
      isManager = {{ auth('manager') ? 'true' : 'false' }};
      isArchiver = {{ auth('archiver') ? 'true' : 'false' }};
    </script>

    <script src="{{ asset('assets/js/jquery.js') }}"></script>

    <script>
      // Include csrf in all requests
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
    </script>

    @isset($scripts)
        @foreach ($scripts as $script)
            <script src="{{ asset('assets/js/' . $script) }}" type="text/javascript"></script>
        @endforeach
    @endisset

</head>

<body>

@auth
<?php $modules = getMrModuleObj()->loadInfo(); ?>
<?php $dashboard = getDashboard()->loadAll();?>

<header class="navbar navbar-default navbar-static-top bs-docs-nav" role="banner">
    <div class="container">
        <div class="navbar-header">
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name') }}</a>
        </div>
        <nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
            <ul class="nav navbar-nav">
                <?php $page = url()->current(); ?>

                @if($dashboard->getCount() === 1)
                <li {{ Route::is('/') ? 'class="active"' : "" }}>
                    <a href="<?php echo url(); ?>">
                        <i class="fa fa-th-large"></i>
                        <span class="visible-lg-inline" data-i18n="nav.main.dashboard"></span>
                    </a>
                </li>
                @else
                <li class="dropdown {{ Route::is('/show/dashboard') ? " active" : "" }}">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-th-large"></i>
                        <span data-i18n="nav.main.dashboard_plural"></span>
                        <b class="caret"></b>
                    </a>
                    <ul class="dashboard dropdown-menu">

                        @foreach($dashboard->getDropdownData('show/dashboard', $page) as $item)

                        <li class="{{ $item->class }}">
                            <a href="{{ $item->url }}">
                                <span class="pull-right">{{ strtoupper($item->hotkey) }}</span>
                                <span class="dropdown-link-text ">{{ $item->display_name }}</span>
                            </a>
                        </li>

                        @endforeach

                    </ul>

                </li>
                @endif

                <li class="dropdown {{ Route::is('/show/reports') ? " active" : "" }}">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bar-chart-o"></i>
                        <span data-i18n="nav.main.reports"></span>
                        <b class="caret"></b>
                    </a>
                    <ul class="report dropdown-menu">

                        @foreach($modules->getDropdownData('reports', 'show/report', $page) as $item)

                        <li class="{{ $item->class }}">
                            <a href="{{ $item->url }}" data-i18n="{{ $item->i18n }}"></a>
                        </li>

                        @endforeach

                    </ul>

                </li>

                <li class="dropdown {{ Route::is('/show/listing') ? " active" : "" }}">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-list-alt"></i>
                        <span data-i18n="nav.main.listings"></span>
                        <b class="caret"></b>
                    </a>
                    <ul class="listing dropdown-menu">

                        @foreach($modules->getDropdownData('listings', 'show/listing', $page) as $item)

                        <li class="{{ $item->class }}">
                            <a href="{{ $item->url }}" data-i18n="{{ $item->i18n }}"></a>
                        </li>

                        @endforeach

                    </ul>

                </li>

                <!-- TODO: Admin Check -->
                @if(true) {* always be admin for now *}
                <li class="dropdown {{ Route::is('/admin/show') ? " active" : "" }}">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-list-alt"></i>
                        <span data-i18n="nav.main.admin"></span>
                        <b class="caret"></b>
                    </a>
                    <ul class="admin dropdown-menu">

                        @foreach(scandir(conf('view_path').'admin') as $list_url)

                        @if( strpos($list_url, 'php'))
                        <?php $page_url = $url.strtok($list_url, '.'); ?>

                        <li<?php echo strpos($page, $page_url)===0?' class="active"':''; ?>>
                            <a href="<?php echo mr_url($url.strtok($list_url, '.')); ?>" data-i18n="nav.admin.<?php echo $name = strtok($list_url, '.'); ?>"></a>
                        </li>

                        @endif

                        <?php endforeach; ?>
                        @foreach($modules->getDropdownData('admin_pages', 'module', $page) as $item)

                        <li class="<?=$item->class?>">
                            <a href="<?=$item->url?>" data-i18n="<?=$item->i18n?>"></a>
                        </li>

                        @endforeach

                    </ul>

                </li>
                @endif

                <li>
                    <a href="#" id="filter-popup" class="filter-popup">
                        <i class="fa fa-filter"></i>
                    </a>
                </li>

            </ul><!-- nav navbar-nav -->

            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-wrench"></i>
                    </a>
                    <ul class="dropdown-menu theme">

                        @foreach(scandir(PUBLIC_ROOT.'assets/themes') AS $theme)

                        @if( $theme != 'fonts' && strpos($theme, '.') === false)

                        <li><a data-switch="{{ $theme }}" href="#">{{ $theme }}</a></li>

                        @endif

                        @endforeach

                    </ul>
                </li>


                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-globe"></i>
                    </a>
                    <ul class="dropdown-menu locale">

                        @foreach(scandir(PUBLIC_ROOT.'assets/locales') AS $list_url)

                        @if( strpos($list_url, 'json'))

                        <?php $lang = strtok($list_url, '.'); ?>

                        <li><a href="<?php echo mr_url($page, false, ['setLng' => $lang]); ?>" data-i18n="nav.lang.<?php echo $lang; ?>"><?php echo $lang; ?></a></li>

                        @endif

                        @endforeach

                    </ul>
                </li>

                <!-- TODO: check for noauth auth mechanism -->

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-user"></i> {{ \Auth::user()['email'] }}
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="{{ url('/logout') }}">
                                <i class="fa fa-power-off"></i>
                                <span data-i18n="nav.user.logout"></span>
                            </a>
                        </li>
                    </ul>
                </li>

                @if(config('munkireport.show_help'))

                <li>
                    <a href="{{ config('munkireport.help_url') }}" target="_blank">
                        <i class="fa fa-question"></i>
                    </a>
                </li>

                @endif

            </ul>

        </nav>
    </div>
</header>
@endauth

@yield('content')
