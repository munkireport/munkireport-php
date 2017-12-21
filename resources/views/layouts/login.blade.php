<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta content="text/html; charset=utf-8" http-equiv="content-type" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ $subdirectory }}assets/themes/{{ $theme }}/bootstrap.min.css" id="bootstrap-stylesheet" />
    <link rel="stylesheet" href="{{ $subdirectory }}assets/nvd3/nv.d3.min.css" />
    <link rel="stylesheet" href="{{ $subdirectory }}assets/themes/{{ $theme }}/nvd3.override.css" id="nvd3-override-stylesheet" />
    <link rel="stylesheet" href="{{ $subdirectory }}assets/css/style.css" />
    <link rel="stylesheet" media="screen" href="{{ $subdirectory }}assets/css/datatables.min.css" />
    <link href="{{ $subdirectory }}assets/css/font-awesome.min.css" rel="stylesheet">
    <!--favicons-->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ $subdirectory }}assets/images/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="{{ $subdirectory }}assets/images/favicons/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="{{ $subdirectory }}assets/images/favicons/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="{{ $subdirectory }}assets/images/favicons/manifest.json">
    <link rel="mask-icon" href="{{ $subdirectory }}assets/images/favicons/safari-pinned-tab.svg" color="#5d5858">
    <link rel="shortcut icon" href="{{ $subdirectory }}assets/images/favicons/favicon.ico">
    <meta name="msapplication-config" content="{{ $subdirectory }}assets/images/favicons/browserconfig.xml">
    <meta name="theme-color" content="#5d5858">
    <!--end of favicons-->
    @isset($custom_css)
        <link rel="stylesheet" href="{{ $custom_css }}" />
    @endisset
    @stack('stylesheets')
    @isset($stylesheets)
        @foreach($stylesheets as $stylesheet)
            <link rel="stylesheet" href="{{ $subdirectory }}assets/css/{{ $stylesheet }}" />
        @endforeach
    @endisset
    <script>
      var baseUrl = "{{ $subdirectory }}";
      var appUrl = "{{ $appUrl }}";

      @if ($enable_business_units)
        var businessUnitsEnabled = true;
      @else
        var businessUnitsEnabled = false;
      @endif

      @if ($role == 'admin')
        var isAdmin = true;
      @else
        var isAdmin = false;
      @endif

      @if ($role == 'manager')
        var isManager = true;
      @else
        var isManager = false;
      @endif
    </script>
    <script src="{{ $subdirectory }}assets/js/jquery.js"></script>

    @isset($scripts)
        @foreach($scripts as $script)
            <script src="{{ $subdirectory }}assets/js/{{ $script }}" type="text/javascript"></script>
        @endforeach
    @endisset

</head>

<body>
    @yield('content')
    @stack('scripts')
</body>
</html>
