<!doctype html>
<html class="no-js" lang="en">

<head>
  <meta content="text/html; charset=utf-8" http-equiv="content-type" />
  <title><?=Config::get('siteName')?></title>
  <link rel="stylesheet" type="text/css" media="screen" href="<?=WEB_FOLDER?>assets/css/bootstrap.min.css" />
  <link rel="stylesheet" type="text/css" media="screen" href="<?=WEB_FOLDER?>assets/css/style.css" />
  <link rel="stylesheet" type="text/css" media="screen" href="<?=WEB_FOLDER?>assets/css/bootstrap-responsive.min.css" />
  <link rel="stylesheet" type="text/css" media="screen" href="<?=WEB_FOLDER?>assets/css/dataTables-bootstrap.css" />
  <link href="<?=WEB_FOLDER?>assets/css/font-awesome.css" rel="stylesheet">
  <script src="<?=WEB_FOLDER?>assets/js/jquery.js"></script>

  <style type="text/css">
  .progress{margin-bottom: 0}
  </style>
<?php
  if (isset($scripts))
    foreach($scripts as $script): ?>
  <script src="<?=WEB_FOLDER?>assets/js/<?=$script?>" type="text/javascript"></script>
<?php endforeach; ?>
</head>

<body>

  <?if( isset($_SESSION['user'])):?>


      <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="<?=url('')?>"><?=Config::get('siteName')?></a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <?
                $page = $GLOBALS[ 'engine' ]->get_uri_string();
                $navlist = array( 
                  ''                => array('th-large', 'Dashboard'), 
                  'clients'         => array('group', 'Clients'), 
                  'show/reports'    => array('bar-chart', 'Reports'),
                  'inventory'       => array('credit-card', 'Inventory'),
                  'inventory/items' => array('info-sign', 'Bundles'),
                  'munki/manifests' => array('edit', 'Manifests'),
                  'munki/catalogs'  => array('list-alt', 'Catalogs')
                )?>
                <?foreach($navlist as $url => $obj):?>
              <li <?=$page==$url?'class="active"':''?>>
                <a href="<?=url($url)?>"><i class="icon-<?=$obj[0]?>"></i> <?=$obj[1]?></a>
              </li>
                <?endforeach?>
            </ul>
            <form class="navbar-form pull-right">
              <a class="btn" href="<?=url('auth/logout')?>">Logout</a>
            </form>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

  <?endif?>