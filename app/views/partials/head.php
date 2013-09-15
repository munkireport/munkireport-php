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

  <script type="text/javascript">

  // Datatables defaults
  $(document).ready(function() {
      $.extend( $.fn.dataTable.defaults, {
        "sDom": "<'row'<'col-lg-6 col-md-6'l r><'col-lg-6 col-md-6'f>>t<'row'<'col-lg-6 col-md-6'i><'col-lg-6 col-md-6'p>>",
        "bStateSave": true,
        "fnStateSave": function (oSettings, oData) {
            state( oSettings.sTableId, oData);
        },
        "fnStateLoad": function (oSettings) {
            return state(oSettings.sTableId);
        },
        "fnInitComplete": function(oSettings, json) {
          $(this).wrap('<div class="table-responsive" />'); // Wrap table in responsive div
        },
        "sPaginationType": "bootstrap"
      } );

      // Set/retrieve state data in localStorage
      function state(id, data)
      {
        // Create unique id for this page
        path = location.pathname + location.search
        // Strip host information and index.php
        path = path.replace(/.*index\.php\??/, '')
        // Strip serial number from detail page, we don't want to store
        // sorting information for every unique client
        path = path.replace(/(.*\/clients\/detail\/).+$/, '$1')
        // Strip inventory item from page, no unique sort per item
        path = path.replace(/(.*\/inventory\/items\/).+$/, '$1')
        // Append id to page path
        id = path + id

        if( data == undefined)
        {
          // Get data
          return JSON.parse( localStorage.getItem(id) );

        }
        else
        {
          // Set data
          localStorage.setItem( id, JSON.stringify(data) );
        }
      }
  } );
  </script>


  <style type="text/css">

	/* Stretch to 98% for large screens */
	@media (min-width: 1200px)
	{
		.container {
		max-width: 98%;
		}
	}
	
  .progress{margin-bottom: 0}
  h2 {
    margin-bottom: 20px;
    font-size: 21px;
    line-height: 40px;
    color: #333;
    border: 0;
    border-bottom: 1px solid #e5e5e5
  }
  .bigger-150
  {
    font-size:150%
  }
  </style>
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
              <?$url = 'show/listing'?>
              <li class="dropdown<?=strpos($page, $url)===0?' active':''?>">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-list-alt"></i> Listings <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="<?=url('show/listing/clients')?>">Clients</a></li>
                  <li><a href="<?=url('show/listing/munki')?>">Munkireport</a></li>
                  <li><a href="<?=url('show/listing/disk')?>">Disk</a></li>
                  <li><a href="<?=url('show/listing/warranty')?>">Warranty</a></li>
                  <li><a href="<?=url('show/listing/hardware')?>">Hardware</a></li>
                  <li><a href="<?=url('show/listing/inventory')?>">Inventory</a></li>
                </ul>
              </li>
          </ul>
          <div class="navbar-form pull-right">
            <a class="btn btn-default btn-sm" href="<?=url('auth/logout')?>">Logout</a>
          </div>
    </nav>
  </div>
</header>

  <?endif?>