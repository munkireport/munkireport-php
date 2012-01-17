<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
                      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:py="http://genshi.edgewall.org/"
      xmlns:xi="http://www.w3.org/2001/XInclude"
      py:strip="">
<head>
    <meta content="text/html; charset=UTF-8" http-equiv="content-type" />
    <title>MunkiReport</title>
	<link rel="stylesheet" type="text/css" media="screen" href="<?=WEB_FOLDER?>assets/css/style.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="<?=WEB_FOLDER?>assets/css/admin.css" />
	
	<script type="text/javascript" language="javascript" src="<?=WEB_FOLDER?>assets/js/jquery.js"></script>
	<script type="text/javascript" language="javascript" src="<?=WEB_FOLDER?>assets/js/jquery.dataTables.min.js"></script>
	
</head>

<body py:match="body" py:attrs="select('@*')">
  <div id="header">
  	<h1>MunkiReport</h1>
  </div>
  
  <ul id="mainmenu">
    <li class="first"><a href="<?=url()?>" <?=isset($page) && $page=='index'?'class="active"':''?>>Start</a></li>
    <li><a href="<?=url('show/reports')?>" <?=isset($page) && $page=='reports'?'class="active"':''?>>Reports</a></li>
    <!--!
        <li><a href="${tg.url('/about')}" class="${('', 'active')[defined('page') and page=='about']}">About</a></li>
        <li py:if="tg.auth_stack_enabled"><a href="${tg.url('/auth')}" class="${('', 'active')[defined('page') and page=='auth']}">Authentication</a></li>
        <li><a href="${tg.url('/environ')}" class="${('', 'active')[defined('page') and page=='environ']}">WSGI Environment</a></li>
        <li><a href="http://groups.google.com/group/turbogears">Contact</a></li>
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