<!doctype html>
<html class="no-js" lang="en">

<head>
  <meta content="text/html; charset=utf-8" http-equiv="content-type" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title><?php echo conf('sitename'); ?></title>
  <link rel="stylesheet" type="text/css" href="<?php echo conf('subdirectory'); ?>assets/css/bootstrap.min.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo conf('subdirectory'); ?>assets/css/style.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo conf('subdirectory'); ?>assets/nvd3/nv.d3.min.css" />
  <link rel="stylesheet" type="text/css" media="screen" href="<?php echo conf('subdirectory'); ?>assets/css/dataTables-bootstrap.css" />
  <link href="<?php echo conf('subdirectory'); ?>assets/css/font-awesome.min.css" rel="stylesheet">
  <?php if(conf('custom_css')): ?> 
  <link rel="stylesheet" href="<?php echo conf('custom_css'); ?>" />
  <?php endif; ?>

  <script>
    var baseUrl = "<?php echo conf('subdirectory'); ?>";
  </script>
  
  <script src="<?php echo conf('subdirectory'); ?>assets/js/jquery.js"></script>


<?php
  if (isset($scripts))
    foreach($scripts as $script): ?>
  <script src="<?php echo conf('subdirectory'); ?>assets/js/<?php echo $script; ?>" type="text/javascript"></script>
<?php endforeach; ?>
</head>

<body>

  <?php if( isset($_SESSION['user'])):?>

<header class="navbar navbar-inverse navbar-fixed-top bs-docs-nav" role="banner">
  <div class="container">
    <div class="navbar-header">
      <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo url(''); ?>"><?php echo conf('sitename'); ?></a>
    </div>
    <nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
      <ul class="nav navbar-nav">
        <?php $page = $GLOBALS[ 'engine' ]->get_uri_string(); ?>

            <li <?php echo $page==''?'class="active"':''; ?>>
              <a href="<?php echo url(); ?>"><i class="fa fa-th-large"></i> <span data-i18n="nav.main.dashboard">Dashboard</span></a>
            </li>

              <?php $url = 'show/reports/'; ?>
              <li class="dropdown<?php echo strpos($page, $url)===0?' active':''; ?>">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bar-chart-o"></i> <span data-i18n="nav.main.reports">Reports</span> <b class="caret"></b></a>
                <ul class="dropdown-menu">

                  <?php foreach(scandir(conf('view_path').'report') AS $list_url): ?>

                    <?php if( strpos($list_url, 'php')): ?>
                    <?php $page_url = $url.strtok($list_url, '.'); ?>

                    <li<?php echo strpos($page, $page_url)===0?' class="active"':''; ?>>
                      <a href="<?php echo url($page_url); ?>" data-i18n="nav.reports.<?php echo $name = strtok($list_url, '.'); ?>"><?php echo ucfirst($name); ?></a>
                    </li>

                    <?php endif; ?>

                  <?php endforeach; ?>

                </ul>

              </li>

              <?php $url = 'show/listing/'; ?>
              <li class="dropdown<?php echo strpos($page, $url)===0?' active':''; ?>">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-list-alt"></i> <span data-i18n="nav.main.listings">Listings</span> <b class="caret"></b></a>
                <ul class="dropdown-menu">

                  <?php foreach(scandir(conf('view_path').'listing') AS $list_url): ?>

                    <?php if( strpos($list_url, 'php')): ?>
                    <?php $page_url = $url.strtok($list_url, '.'); ?>

                    <li<?php echo strpos($page, $page_url)===0?' class="active"':''; ?>>
                      <a href="<?php echo url($url.strtok($list_url, '.')); ?>" data-i18n="nav.listings.<?php echo $name = strtok($list_url, '.'); ?>"><?php echo ucfirst($name); ?></a>
                    </li>

                    <?php endif; ?>

                  <?php endforeach; ?>

                </ul>

              </li>

          </ul>
          <?php $auth = conf('auth'); // Hide logout button if auth_noauth
            if( ! array_key_exists('auth_noauth', $auth)): ?>

          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-globe"></i></a>
              <ul class="dropdown-menu locale">

                  <?php foreach(scandir(APP_ROOT.'assets/locales') AS $list_url): ?>

                    <?php if( strpos($list_url, 'json')):?>

                    <?php $lang = strtok($list_url, '.'); ?>

                    <li><a href="<?php echo url("$page&amp;setLng=$lang"); ?>" data-i18n="nav.lang.<?php echo $lang; ?>"><?php echo $lang; ?></a></li>

                    <?php endif; ?>

                  <?php endforeach; ?>

                </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $_SESSION['user']; ?> <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="<?php echo url('auth/logout'); ?>"><i class="fa fa-power-off"></i> <span data-i18n="nav.user.logout">Sign Off</span></a></li>
              </ul>
            </li>
          </ul>

          <?php endif; ?>
    </nav>
  </div>
</header>



  <?php endif; ?>