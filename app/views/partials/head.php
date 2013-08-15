<!doctype html>
<html class="no-js" lang="en">

<head>
  <meta content="text/html; charset=utf-8" http-equiv="content-type" />
  <title><?=Config::get('siteName')?></title>
  <link rel="stylesheet" type="text/css" media="screen" href="<?=WEB_FOLDER?>assets/css/bootstrap.min.css" />
  <link rel="stylesheet" type="text/css" media="screen" href="<?=WEB_FOLDER?>assets/css/style.css" />
  <link rel="stylesheet" type="text/css" media="screen" href="<?=WEB_FOLDER?>assets/css/dataTables-bootstrap.css" />
  <link href="<?=WEB_FOLDER?>assets/css/font-awesome.css" rel="stylesheet">
  <script src="<?=WEB_FOLDER?>assets/js/jquery.js"></script>


  <style type="text/css">
  .progress{margin-bottom: 0}
  h2 {
    margin-bottom: 20px;
    font-size: 21px;
    line-height: 40px;
    color: #333;
    border: 0;
    border-bottom: 1px solid #e5e5e5
}
  </style>
<?php
  if (isset($scripts))
    foreach($scripts as $script): ?>
  <script src="<?=WEB_FOLDER?>assets/js/<?=$script?>" type="text/javascript"></script>
<?php endforeach; ?>
</head>

<body>

  <?if( isset($_SESSION['user'])):?>

  <div class="navbar navbar-inverse navbar-fixed-top bs-docs-nav">
    <div class="container">
      <div class="navbar-header">
      <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?=url('')?>"><?=Config::get('siteName')?></a>
    </div>
      <div class="nav-collapse collapse bs-navbar-collapse">
        <ul class="nav navbar-nav">      
              <?
              $page = $GLOBALS[ 'engine' ]->get_uri_string();
              $navlist = array( 
                '' => (object) array('icon' => 'th-large', 'title' => 'Dashboard'), 
                'show/reports' => (object) array('icon' => 'bar-chart', 'title' => 'Reports'),
                'inventory/items' => (object) array('icon' => 'info-sign', 'title' => 'Bundles'),
                );

                ?>
              <?foreach($navlist as $url => $obj):?>
            <li <?=$page==$url?'class="active"':''?>>
              <a href="<?=url($url)?>"><i class="icon-<?=$obj->icon?>"></i> <?=$obj->title?></a>
            </li>
              <?endforeach?>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-list-alt"></i> Listings <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="<?=url('show/listing/clients')?>">Clients</a></li>
                  <li><a href="<?=url('show/listing/munki')?>">Munkireport</a></li>
                  <li><a href="<?=url('show/listing/disk')?>">Disk</a></li>
                  <li><a href="<?=url('show/listing/warranty')?>">Warranty</a></li>
                </ul>
              </li>
          </ul>
          <form class="navbar-form pull-right">
            <a class="btn btn-default btn-sm" href="<?=url('auth/logout')?>">Logout</a>
          </form>
      </div>
    </div>
  </div>

  <?endif?>