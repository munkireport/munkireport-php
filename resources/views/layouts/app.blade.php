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
    @foreach($stylesheets as $stylesheet)
        <link rel="stylesheet" href="{{ $subdirectory }}assets/css/{{ $stylesheet }}" />
    @endforeach
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

    @foreach($scripts as $script)
        <script src="{{ $subdirectory }}assets/js/{{ $script }}" type="text/javascript"></script>
    @endforeach

</head>

<body>

@isset($user)
<?php $modules = getMrModuleObj()->loadInfo(); ?>

@include('shared.navbar')


@endisset

<!-- end header -->

@yield('content')

<div class="container">

    <div style="text-align: right; margin: 10px; color: #bbb; font-size: 80%;">

        <i>MunkiReport <span data-i18n="version">Version</span> {{ $version }}</i>

    </div>

</div>

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

<?php foreach($alerts AS $type => $list): ?>

<div class="mr-alert alert alert-dismissable alert-<?php echo $type; ?>">

    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

    <ul>

        @foreach ($list as $msg)
            <li>{{ $msg }}</li>
        @endforeach

    </ul>

</div>

<?php endforeach; ?>

<script>
  $('.mr-alert').prependTo('body>div.container:first');
</script>


<script src="{{ $subdirectory }}assets/js/bootstrap.min.js"></script>
<script src="{{ $subdirectory }}assets/js/datatables.min.js"></script>
<script src="{{ $subdirectory }}assets/js/moment.min.js"></script>
<script src="{{ $subdirectory }}assets/js/i18next.min.js"></script>
<script src="{{ $subdirectory }}assets/js/d3/d3.min.js"></script>
<script src="{{ $subdirectory }}assets/js/nv.d3.min.js"></script>
<script src="{{ $subdirectory }}assets/js/jquery.hotkeys/jquery.hotkeys.js"></script>
<script src="{{ $subdirectory }}assets/js/munkireport.settings.js"></script>

<script>
  // Inject debug value from php
  @if ($debug)
      mr.debug = true;
  @else
      mr.debug = false;
  @endif
</script>

@if ($custom_js)
    <script src="{{ $custom_js }}"></script>
@endif

<script src="{{ $subdirectory }}assets/js/munkireport.js"></script>

<?php if(isset($recaptcha) && $recaptchaloginpublickey):?>
<script src='https://www.google.com/recaptcha/api.js' async defer></script>
<script>
  function onSubmit(token) {
    document.getElementById("login-form").submit();
  }
</script>
<?php endif?>

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
