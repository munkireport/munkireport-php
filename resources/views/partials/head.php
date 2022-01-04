<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="<?php echo csrf_token(); ?>">

	<meta name=apple-mobile-web-app-capable content=yes>
	<meta content="text/html; charset=utf-8" http-equiv="content-type" />

    <title><?php echo config('app.name', 'MunkiReport'); ?></title>

    <!-- Styles -->
    <?php if (config('frontend.css.use_cdn', false)): ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.24/b-1.7.0/b-html5-1.7.0/b-print-1.7.0/datatables.min.css"/>
    <?php else: ?>
        <!-- bootstrap.min.js is loaded locally using the `Default` theme -->
        <link rel="stylesheet" href="<?php echo asset('assets/css/datatables.bootstrap4.min.css'); ?>" />
        <link rel="stylesheet" href="<?php echo asset('assets/css/buttons.bootstrap4.min.css'); ?>" />
    <?php endif ?>

    <link rel="stylesheet" href="<?php echo asset('assets/css/font-awesome.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo asset('assets/nvd3/nv.d3.min.css'); ?>" />
	<link rel="stylesheet" href="<?php echo asset('assets/themes/' . sess_get('theme', 'Default') . '/bootstrap.min.css'); ?>" id="bootstrap-stylesheet" />
	<link rel="stylesheet" href="<?php echo asset('assets/themes/' . sess_get('theme', 'Default') . '/nvd3.override.css'); ?>" id="nvd3-override-stylesheet" />
	<link rel="stylesheet" href="<?php echo asset('assets/css/style.css'); ?>" />

    <!-- Head scripts -->
    <?php if (config('frontend.javascript.use_cdn', false)): ?>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12"></script>
    <?php else: ?>
        <script src="<?php echo asset('assets/js/jquery.min.js'); ?>"></script>
        <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12"></script>
    <?php endif ?>
    <script>
      // Include csrf in all requests
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
    </script>

    <!-- Favicons -->
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo asset('assets/images/favicons/apple-touch-icon.png'); ?>">
	<link rel="icon" type="image/png" href="<?php echo asset('assets/images/favicons/favicon-32x32.png'); ?>" sizes="32x32">
	<link rel="icon" type="image/png" href="<?php echo asset('assets/images/favicons/favicon-16x16.png'); ?>" sizes="16x16">
	<link rel="manifest" href="<?php echo asset('assets/images/favicons/manifest.json'); ?>">
	<link rel="mask-icon" href="<?php echo asset('assets/images/favicons/safari-pinned-tab.svg'); ?>" color="#5d5858">
	<link rel="shortcut icon" href="<?php echo asset('assets/images/favicons/favicon.ico'); ?>">
	<meta name="msapplication-config" content="<?php echo asset('assets/images/favicons/browserconfig.xml'); ?>">
	<meta name="theme-color" content="#5d5858">

    <!-- munkireport.custom_css -->
	<?php if(config('_munkireport.custom_css')): ?>
	<link rel="stylesheet" href="<?php echo config('_munkireport.custom_css'); ?>" />
	<?php endif; ?>

    <!-- $stylesheets -->
	<?php if(isset($stylesheets)):?>
	    <?php foreach($stylesheets as $stylesheet):?>
	        <link rel="stylesheet" href="<?php echo asset('assets/css/' . $stylesheet); ?>" />
	    <?php endforeach?>
	<?php endif?>

	<script>
		var baseUrl = "<?php echo conf('subdirectory'); ?>",
			appUrl = "<?php echo url('/'); ?>",
			default_theme = "<?php echo config('_munkireport.default_theme'); ?>",
			businessUnitsEnabled = <?php echo config('_munkireport.enable_business_units') ? 'true' : 'false'; ?>;
			isAdmin = <?php echo \Auth::user()['role'] == 'admin' ? 'true' : 'false'; ?>;
			isManager = <?php echo \Auth::user()['role'] == 'manager' ? 'true' : 'false'; ?>;
			isArchiver = <?php echo \Auth::user()['role'] == 'archiver' ? 'true' : 'false'; ?>;
	</script>

<?php
	if (isset($scripts)) {
		foreach($scripts as $script) { ?>
	        <script src="<?php echo asset('assets/js/' . $script); ?>" type="text/javascript"></script>
            <?php
		}
    }
?>
</head>

<body class="head-partial" style="padding-top: 56px;">
<?php
    /* if( isset($_SESSION['user'])): */
    // if Auth:: authenticated :

    $modules = getMrModuleObj()->loadInfo();
    $dashboard = getDashboard()->loadAll();
    $page = url()->current();
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top"> <!-- bs-docs-nav -->
    <a class="navbar-brand" href="<?php echo url(''); ?>"><?php echo config('app.name'); ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse bs-navbar-collapse" id="navbarSupportedContent">
        <div class="navbar-nav mr-auto">
            <?php if($dashboard->getCount() === 1) { ?>
				<li <?php echo $page==''?'class="nav-item active"':'class="nav-item"'; ?>>
					<a class="nav-link" href="<?php echo url('/'); ?>">
						<i class="fa fa-th-large"></i>
						<span class="visible-lg-inline" data-i18n="nav.main.dashboard"></span>
					</a>
				</li>
            <?php } else { ?>
                <?php $url = 'show/dashboard/'; ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-th-large"></i>
                        <span data-i18n="nav.main.dashboard_plural"></span>
                        <b class="caret"></b>
                    </a>
                    <div class="dashboard dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <?php foreach($dashboard->getDropdownData('show/dashboard', $page) as $item): ?>
                            <a class="dropdown-item <?=$item->class?>" href="<?=$item->url?>">
                                <span class="pull-right"><?=strtoupper($item->hotkey)?></span>
                                <span class="dropdown-link-text "><?=$item->display_name?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </li>
            <?php } ?>

            <?php $url = 'show/reports/'; ?>
            <li class="nav-item dropdown<?php echo strpos($page, $url)===0?' active':''; ?>">
                <a class="nav-link dropdown-toggle" href="#" role="button" id="reportsMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-bar-chart-o"></i>
                    <span data-i18n="nav.main.reports"></span>
                    <b class="caret"></b>
                </a>
                <div class="report dropdown-menu">
                <?php foreach($modules->getDropdownData('reports', 'show/report', $page) as $item): ?>
                    <a class="dropdown-item <?=$item->class?>" href="<?=$item->url?>" data-i18n="<?=$item->i18n?>"></a>
                <?php endforeach; ?>
                </div>
            </li>

            <?php $url = 'show/listing/'; ?>
            <li class="nav-item dropdown<?php echo strpos($page, $url)===0?' active':''; ?>">
                <a class="nav-link dropdown-toggle" href="#" role="button" id="listingMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-list-alt"></i>
                    <span data-i18n="nav.main.listings"></span>
                    <b class="caret"></b>
                </a>
                <div class="listing dropdown-menu" aria-labelledby="listingMenuLink">
                <?php foreach($modules->getDropdownData('listings', 'show/listing', $page) as $item): ?>
                    <a class="dropdown-item <?=$item->class?>" href="<?=$item->url?>" data-i18n="<?=$item->i18n?>"></a>
                <?php endforeach; ?>
                </div>
            </li>

            <?php if(Gate::allows('global')): ?>
            <?php $url = 'admin/show/'; ?>
            <li class="dropdown<?php echo strpos($page, $url)===0?' active':''; ?>">
                <a class="nav-link dropdown-toggle" href="#" role="button" id="adminMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-list-alt"></i>
                    <span data-i18n="nav.main.admin"></span>
                    <b class="caret"></b>
                </a>
                <div class="admin dropdown-menu"  aria-labelledby="adminMenuLink">
                    <?php
                    foreach(scandir(conf('view_path').'admin') as $list_url) {
                        if( strpos($list_url, 'php')) {
                            $page_url = $url.strtok($list_url, '.');
                            $classes = "dropdown-item";
                            if (strpos($page, $page_url) === 0) { $classes .= " active"; }
                            ?>
                            <a class="<?php echo $classes; ?>"
                               href="<?php echo mr_url($url.strtok($list_url, '.')); ?>"
                               data-i18n="nav.admin.<?php echo $name = strtok($list_url, '.'); ?>"></a>
                            <?php
                        }
                    }
                    ?>
                    <?php foreach($modules->getDropdownData('admin_pages', 'module', $page) as $item): ?>
                        <!-- <li class="<?=$item->class?>"> -->
                            <a class="dropdown-item" href="<?=$item->url?>" data-i18n="<?=$item->i18n?>"></a>
                        <!-- </li> -->
                    <?php endforeach; ?>
                </div>
            </li>
            <?php endif?>
            <li class="nav-item">
                <a href="#" id="filter-popup" class="nav-link filter-popup">
                    <i class="fa fa-filter"></i>
                </a>
            </li>
        </div><!-- div navbar-nav mr-auto (left aligned) -->

        <div class="navbar-nav ml-auto">
            <?php if (config('_munkireport.alpha_features.search', false)): ?>
            <form class="form-inline my-2 my-lg-0">
                <li class="dropdown" data-reference="parent">
                    <div class="search-results dropdown-menu">
                        <a class="dropdown-item" href="#">No results</a>
                    </div>
                </li>
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
            <?php endif ?>

            <li class="dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" id="themeMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-wrench"></i>
                </a>
                <div class="theme dropdown-menu" aria-labelledby="themeMenuLink">
                    <?php foreach(scandir(PUBLIC_ROOT.'assets/themes') AS $theme): ?>
                        <?php if( $theme != 'fonts' && strpos($theme, '.') === false):?>
                            <a class="dropdown-item" data-switch="<?php echo $theme; ?>" href="#"><?php echo $theme; ?></a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </li>

            <li class="dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" id="localeMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-globe"></i>
                </a>
                <div class="locale dropdown-menu" aria-labelledby="localeMenuLink">
                    <?php foreach(scandir(PUBLIC_ROOT.'assets/locales') AS $list_url): ?>
                        <?php if( strpos($list_url, 'json')):?>
                            <?php $lang = strtok($list_url, '.'); ?>
                            <a class="dropdown-item"
                               href="<?php echo mr_url($page, false, ['setLng' => $lang]); ?>"
                               data-i18n="nav.lang.<?php echo $lang; ?>"><?php echo $lang; ?></a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </li>

            <li class="dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" id="userMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-user"></i> <?php echo \Auth::user()['email']; ?>
                    <b class="caret"></b>
                </a>

                <div class="dropdown-menu" aria-labelledby="userMenuLink">
                    <a class="dropdown-item" href="<?php echo url('/me/tokens'); ?>" data-i18n="nav.user.tokens">My API Tokens</a>
                    <div class="dropdown-divider"></div>

                    <form action="<?php echo route('logout'); ?>" method="POST">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>" />
                    <button class="dropdown-item" type="submit">
                        <i class="fa fa-power-off"></i>
                        <span data-i18n="nav.user.logout"></span>
                    </button>
                    </form>

                </div>
            </li>

            <?php if(config('_munkireport.show_help')):?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo config('_munkireport.help_url');?>" target="_blank">
                        <i class="fa fa-question"></i>
                    </a>
                </li>
            <?php endif; ?>
        </div><!-- div navbar-nav ml-auto (right aligned) -->

    </div><!-- navbar-collapse -->
</nav><!-- nav -->

<?php /* endif; isset($_SESSION['user'])) */ ?>
