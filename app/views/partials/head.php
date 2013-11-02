<!doctype html>
<html class="no-js" lang="en">

<head>
  <meta content="text/html; charset=utf-8" http-equiv="content-type" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title><?=conf('sitename')?></title>
  <link rel="stylesheet" type="text/css" media="screen" href="<?=conf('subdirectory')?>assets/css/bootstrap.min.css" />
  <link rel="stylesheet" type="text/css" media="screen" href="<?=conf('subdirectory')?>assets/css/style.css" />
  <link rel="stylesheet" type="text/css" media="screen" href="<?=conf('subdirectory')?>assets/css/dataTables-bootstrap.css" />
  <link href="<?=conf('subdirectory')?>assets/css/font-awesome.min.css" rel="stylesheet">
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
        <?
              $page = $GLOBALS[ 'engine' ]->get_uri_string();
              $navlist = array( 
                '' => (object) array('icon' => 'th-large', 'title' => 'Dashboard'), 
                'show/reports' => (object) array('icon' => 'bar-chart', 'title' => 'Reports')
                );

                ?>
              <?foreach($navlist as $url => $obj):?>
            <li <?=$page==$url?'class="active"':''?>>
              <a href="<?=url($url)?>"><i class="icon-<?=$obj->icon?>"></i> <?=$obj->title?></a>
            </li>
              <?endforeach?>
              <?$url = 'show/listing/'?>
              <li class="dropdown<?=strpos($page, $url)===0?' active':''?>">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-list-alt"></i> Listings <b class="caret"></b></a>
                <ul class="dropdown-menu">

                  <?foreach(scandir(conf('view_path').'listing') AS $list_url):?>

                    <?if( strpos($list_url, 'php')):?>

                    <li><a href="<?=url($url.strtok($list_url, '.'))?>"><?=ucfirst(strtok($list_url, '.'))?></a></li>

                    <?endif?>

                  <?endforeach?>

                </ul>

              </li>

          </ul>
          <?$auth = conf('auth'); // Hide logout button if auth_noauth
            if( ! array_key_exists('auth_noauth', $auth)):?>
          <div class="navbar-form pull-right">
            <a class="btn btn-default btn-sm" href="<?=url('auth/logout')?>">Logout <?=$_SESSION['user']?></a>
          </div>
          <?endif?>
    </nav>
  </div>
</header>



  <?endif?>