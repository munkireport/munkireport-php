<!doctype html>
<html class="no-js" lang="en">

<head>
  <meta content="text/html; charset=utf-8" http-equiv="content-type" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title><?=conf('sitename')?></title>
  <link rel="stylesheet" type="text/css" href="<?=conf('subdirectory')?>assets/css/bootstrap.min.css" />
  <link rel="stylesheet" type="text/css" href="<?=conf('subdirectory')?>assets/css/style.css" />
  <link rel="stylesheet" type="text/css" media="screen" href="<?=conf('subdirectory')?>assets/css/dataTables-bootstrap.css" />
  <link href="<?=conf('subdirectory')?>assets/css/font-awesome.min.css" rel="stylesheet">
  <?if(conf('custom_css')):?> 
  <link rel="stylesheet" href="<?=conf('custom_css')?>" />
  <?endif?>

  <script>
    var baseUrl = "<?=conf('subdirectory')?>";
  </script>
  
  <script src="<?=conf('subdirectory')?>assets/js/jquery.js"></script>


<?php
  if (isset($scripts))
    foreach($scripts as $script): ?>
  <script src="<?=conf('subdirectory')?>assets/js/<?=$script?>" type="text/javascript"></script>
<?php endforeach; ?>
</head>

<body>

  <?if( isset($_SESSION['user'])):?>

<header class="navbar navbar-inverse navbar-fixed-top bs-docs-nav" role="banner">
  <div class="container">
    <div class="navbar-header">
      <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?=url('')?>"><?=conf('sitename')?></a>
    </div>
    <nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
      <ul class="nav navbar-nav">
        <?$page = $GLOBALS[ 'engine' ]->get_uri_string()?>

            <li <?=$page==''?'class="active"':''?>>
              <a href="<?=url()?>"><i class="fa fa-th-large"></i> <span data-i18n="nav.main.dashboard">Dashboard</span></a>
            </li>

              <?$url = 'show/reports/'?>
              <li class="dropdown<?=strpos($page, $url)===0?' active':''?>">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bar-chart-o"></i> <span data-i18n="nav.main.reports">Reports</span> <b class="caret"></b></a>
                <ul class="dropdown-menu">

                  <?foreach(scandir(conf('view_path').'report') AS $list_url):?>

                    <?if( strpos($list_url, 'php')):?>
                    <?$page_url = $url.strtok($list_url, '.')?>

                    <li<?=strpos($page, $page_url)===0?' class="active"':''?>>
                      <a href="<?=url($page_url)?>" data-i18n="nav.reports.<?=$name = strtok($list_url, '.')?>"><?=ucfirst($name)?></a>
                    </li>

                    <?endif?>

                  <?endforeach?>

                </ul>

              </li>

              <?$url = 'show/listing/'?>
              <li class="dropdown<?=strpos($page, $url)===0?' active':''?>">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-list-alt"></i> <span data-i18n="nav.main.listings">Listings</span> <b class="caret"></b></a>
                <ul class="dropdown-menu">

                  <?foreach(scandir(conf('view_path').'listing') AS $list_url):?>

                    <?if( strpos($list_url, 'php')):?>
                    <?$page_url = $url.strtok($list_url, '.')?>

                    <li<?=strpos($page, $page_url)===0?' class="active"':''?>>
                      <a href="<?=url($url.strtok($list_url, '.'))?>" data-i18n="nav.listings.<?=$name = strtok($list_url, '.')?>"><?=ucfirst($name)?></a>
                    </li>

                    <?endif?>

                  <?endforeach?>

                </ul>

              </li>

          </ul>
          <?$auth = conf('auth'); // Hide logout button if auth_noauth
            if( ! array_key_exists('auth_noauth', $auth)):?>

          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-globe"></i></a>
              <ul class="dropdown-menu locale">

                  <?foreach(scandir(APP_ROOT.'assets/locales') AS $list_url):?>

                    <?if( strpos($list_url, 'json')):?>

                    <?$lang = strtok($list_url, '.')?>

                    <li><a href="<?=url("$page&amp;setLng=$lang")?>" data-i18n="nav.lang.<?=$lang?>"><?=$lang?></a></li>

                    <?endif?>

                  <?endforeach?>

                </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?=$_SESSION['user']?> <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="<?=url('auth/logout')?>"><i class="fa fa-power-off"></i> <span data-i18n="nav.user.logout">Sign Off</span></a></li>
              </ul>
            </li>
          </ul>

          <?endif?>
    </nav>
  </div>
</header>



  <?endif?>