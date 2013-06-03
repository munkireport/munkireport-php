<!doctype html>
<html class="no-js" lang="en">

<head>
  <meta content="text/html; charset=utf-8" http-equiv="content-type" />
  <title><?=$GLOBALS['sitename']?></title>
  <link rel="stylesheet" type="text/css" media="screen" href="<?=WEB_FOLDER?>assets/css/bootstrap.min.css" />
  <link rel="stylesheet" type="text/css" media="screen" href="<?=WEB_FOLDER?>assets/css/style.css" />
  <link rel="stylesheet" type="text/css" media="screen" href="<?=WEB_FOLDER?>assets/css/bootstrap-responsive.min.css" />
  <link rel="stylesheet" type="text/css" media="screen" href="<?=WEB_FOLDER?>assets/css/dataTables-bootstrap.css" />
  <link href="<?=WEB_FOLDER?>assets/css/font-awesome.css" rel="stylesheet">
  <script src="<?=WEB_FOLDER?>assets/js/jquery.js"></script>

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
          <a class="brand" href="<?=url('')?>">Localized Munkireport</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <?$page = $GLOBALS[ 'engine' ]->get_uri_string(); $navlist = array( 
                '' => (object) array('icon' => 'th-large', 'title' => 'Dashboard'), 
                'clients' => (object) array('icon' => 'group', 'title' => 'Clients'), 
                'show/reports' => (object) array('icon' => 'bar-chart', 'title' => 'Reports'),
                'inventory' => (object) array('icon' => 'credit-card', 'title' => 'Inventory'),
                'inventory/items' => (object) array('icon' => 'info-sign', 'title' => 'Inventory Items')
                )?>
                <?foreach($navlist as $url => $obj):?>
              <li <?=$page==$url?'class="active"':''?>>
                <a href="<?=url($url)?>"><i class="icon-<?=$obj->icon?>"></i> <?=$obj->title?></a>
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