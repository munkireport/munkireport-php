<!doctype html>
<html>
<head>
	<meta charset="utf-8">

	<!-- Use the .htaccess and remove these lines to avoid edge case issues.
	More info: h5bp.com/i/378 -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  
    <title><?=$GLOBALS['sitename']?></title>
	<link rel="stylesheet" type="text/css" media="screen" href="<?=WEB_FOLDER?>assets/css/style.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="<?=WEB_FOLDER?>assets/css/admin.css" />
	
	<script type="text/javascript" language="javascript" src="<?=WEB_FOLDER?>assets/js/jquery.js"></script>
	<script type="text/javascript" language="javascript" src="<?=WEB_FOLDER?>assets/js/jquery.dataTables.min.js"></script>
	
</head>

<body py:match="body" py:attrs="select('@*')">
  <div id="header">
  	<h1><?=$GLOBALS['sitename']?></h1>
  </div>
  
  <ul id="mainmenu">
    <li class="first"><a href="<?=url()?>" <?=isset($page) && $page=='index'?'class="active"':''?>>Start</a></li>
	<li><a href="<?=url('clients')?>" <?=isset($page) && $page=='clients'?'class="active"':''?>>Clients</a></li>
    <li><a href="<?=url('show/reports')?>" <?=isset($page) && $page=='reports'?'class="active"':''?>>Reports</a></li>
    <li><a href="<?=url('inventory')?>" <?=isset($page) && $page=='inventory'?'class="active"':''?>>Inventory</a></li>
    <li><a href="<?=url('inventory/items')?>" <?=isset($page) && $page=='inventory_items'?'class="active"':''?>>Inventory Items</a></li>
    
    <!--!
        <li><a href="${tg.url('/about')}" class="${('', 'active')[defined('page') and page=='about']}">About</a></li>
        <li py:if="tg.auth_stack_enabled"><a href="${tg.url('/auth')}" class="${('', 'active')[defined('page') and page=='auth']}">Authentication</a></li>
        <li><a href="${tg.url('/environ')}" class="${('', 'active')[defined('page') and page=='environ']}">WSGI Environment</a></li>
    -->
	<?if(FALSE):?>
    <span py:if="tg.auth_stack_enabled" py:strip="True">
        <li py:if="not request.identity" id="login" class="loginlogout"><a href="${tg.url('/login')}">Login</a></li>
        <li py:if="request.identity" id="login" class="loginlogout"><a href="${tg.url('/logout_handler')}">Logout</a></li>
        <li py:if="request.identity" id="admin" class="loginlogout"><a href="${tg.url('/admin')}">Admin</a></li>
    </span>
	<?endif?>
  </ul>
  <div id="content">
    <div>