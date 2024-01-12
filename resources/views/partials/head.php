<!doctype html>
<html class="no-js" lang="en">

<head>
	<meta name=apple-mobile-web-app-capable content=yes>
	<meta content="text/html; charset=utf-8" http-equiv="content-type" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo csrf_token(); ?>">

	<title><?php echo config('app.name'); ?></title>
	<link rel="stylesheet" href="<?php echo asset('assets/themes/' . sess_get('theme', 'Default') . '/bootstrap.min.css'); ?>" id="bootstrap-stylesheet" />
	<link rel="stylesheet" href="<?php echo asset('assets/nvd3/nv.d3.min.css'); ?>" />
	<link rel="stylesheet" href="<?php echo asset('assets/themes/' . sess_get('theme', 'Default') . '/nvd3.override.css'); ?>" id="nvd3-override-stylesheet" />
	<link rel="stylesheet" href="<?php echo asset('assets/css/style.css'); ?>" />
	<link rel="stylesheet" media="screen" href="<?php echo asset('assets/css/datatables.min.css'); ?>" />
	<link href="<?php echo asset('assets/css/font-awesome.min.css'); ?>" rel="stylesheet">
    <!--favicons-->
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo asset('assets/images/favicons/apple-touch-icon.png'); ?>">
	<link rel="icon" type="image/png" href="<?php echo asset('assets/images/favicons/favicon-32x32.png'); ?>" sizes="32x32">
	<link rel="icon" type="image/png" href="<?php echo asset('assets/images/favicons/favicon-16x16.png'); ?>" sizes="16x16">
	<link rel="manifest" href="<?php echo asset('assets/images/favicons/manifest.json'); ?>">
	<link rel="mask-icon" href="<?php echo asset('assets/images/favicons/safari-pinned-tab.svg'); ?>" color="#5d5858">
	<link rel="shortcut icon" href="<?php echo asset('assets/images/favicons/favicon.ico'); ?>">
	<meta name="msapplication-config" content="<?php echo asset('assets/images/favicons/browserconfig.xml'); ?>">
	<meta name="theme-color" content="#5d5858">
    <!--end of favicons-->
	<?php if(config('_munkireport.custom_css')): ?>
	<link rel="stylesheet" href="<?php echo config('_munkireport.custom_css'); ?>" />
	<?php endif; ?>

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

	<script src="<?php echo asset('assets/js/jquery.js'); ?>"></script>

	<script>
		// Include csrf in all requests
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
	</script>

<?php
	if (isset($scripts))
		foreach($scripts as $script): ?>
	<script src="<?php echo asset('assets/js/' . $script); ?>" type="text/javascript"></script>
<?php endforeach; ?>

</head>

<body>

	<?php /* if( isset($_SESSION['user'])): */ ?>
	<?php $modules = getMrModuleObj()->loadInfo(); ?>
	<?php $dashboard = getDashboard()->loadAll();?>
	
<header class="navbar navbar-default navbar-static-top bs-docs-nav" role="banner">
	<div class="container">
		<div class="navbar-header">
			<button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?php echo url(''); ?>"><?php echo config('app.name'); ?></a>
		</div>
		<nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
			<ul class="nav navbar-nav">
				<?php $page = url()->current(); ?>
				
				<?php if($dashboard->getCount() === 1):?>
				<li <?php echo $page==''?'class="active"':''; ?>>
					<a href="<?php echo url('/'); ?>">
						<i class="fa fa-th-large"></i>
						<span class="visible-lg-inline" data-i18n="nav.main.dashboard"></span>
					</a>
				</li>
				<?php else:?>
					<?php $url = 'show/dashboard/'; ?>
					<li class="dropdown<?php echo strpos($page, $url)===0?' active':''; ?>">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-th-large"></i>
							<span data-i18n="nav.main.dashboard_plural"></span>
							<b class="caret"></b>
						</a>
						<ul class="dashboard dropdown-menu">

							<?php foreach($dashboard->getDropdownData('show/dashboard', $page) as $item): ?>

								<li class="<?=$item->class?>">
									<a href="<?=$item->url?>">
										<span class="pull-right"><?=strtoupper($item->hotkey)?></span>
										<span class="dropdown-link-text "><?=$item->display_name?></span>
									</a>
								</li>

							<?php endforeach; ?>

						</ul>

					</li>
				<?php endif?>

				<?php $url = 'show/reports/'; ?>
				<li class="dropdown<?php echo strpos($page, $url)===0?' active':''; ?>">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-bar-chart-o"></i>
						<span data-i18n="nav.main.reports"></span>
						<b class="caret"></b>
					</a>
					<ul class="report dropdown-menu">

						<?php foreach($modules->getDropdownData('reports', 'show/report', $page) as $item): ?>

							<li class="<?=$item->class?>">
							<a href="<?=$item->url?>" data-i18n="<?=$item->i18n?>"></a>
							</li>

						<?php endforeach; ?>

					</ul>

				</li>

				<?php $url = 'show/listing/'; ?>
				<li class="dropdown<?php echo strpos($page, $url)===0?' active':''; ?>">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-list-alt"></i>
						<span data-i18n="nav.main.listings"></span>
						<b class="caret"></b>
					</a>
					<ul class="listing dropdown-menu">

						<?php foreach($modules->getDropdownData('listings', 'show/listing', $page) as $item): ?>

							<li class="<?=$item->class?>">
							<a href="<?=$item->url?>" data-i18n="<?=$item->i18n?>"></a>
							</li>

						<?php endforeach; ?>

					</ul>

				</li>

				<?php if(\Auth::user()['role'] == 'admin'): ?>
				<?php $url = 'admin/show/'; ?>
				<li class="dropdown<?php echo strpos($page, $url)===0?' active':''; ?>">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-list-alt"></i>
						<span data-i18n="nav.main.admin"></span>
						<b class="caret"></b>
					</a>
					<ul class="admin dropdown-menu">

						<?php foreach(scandir(conf('view_path').'admin') as $list_url): ?>

							<?php if( strpos($list_url, 'php')): ?>
							<?php $page_url = $url.strtok($list_url, '.'); ?>

							<li<?php echo strpos($page, $page_url)===0?' class="active"':''; ?>>
								<a href="<?php echo mr_url($url.strtok($list_url, '.')); ?>" data-i18n="nav.admin.<?php echo $name = strtok($list_url, '.'); ?>"></a>
							</li>

							<?php endif; ?>

						<?php endforeach; ?>
						<?php foreach($modules->getDropdownData('admin_pages', 'module', $page) as $item): ?>

							<li class="<?=$item->class?>">
								<a href="<?=$item->url?>" data-i18n="<?=$item->i18n?>"></a>
							</li>

						<?php endforeach; ?>
					</ul>

				</li>
				<?php endif?>

				<li>
					<a href="#" id="filter-popup" class="filter-popup">
						<i class="fa fa-filter"></i>
					</a>
				</li>

			</ul><!-- nav navbar-nav -->

			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-wrench"></i>
					</a>
					<ul class="dropdown-menu theme">

							<?php foreach(scandir(PUBLIC_ROOT.'assets/themes') AS $theme): ?>

								<?php if( $theme != 'fonts' && strpos($theme, '.') === false):?>

								<li><a data-switch="<?php echo $theme; ?>" href="#"><?php echo $theme; ?></a></li>

								<?php endif; ?>

							<?php endforeach; ?>

						</ul>
				</li>


				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-globe"></i>
					</a>
					<ul class="dropdown-menu locale">

							<?php foreach(scandir(PUBLIC_ROOT.'assets/locales') AS $list_url): ?>

								<?php if( strpos($list_url, 'json')):?>

								<?php $lang = strtok($list_url, '.'); ?>

								<li><a href="<?php echo mr_url($page, false, ['setLng' => $lang]); ?>" data-i18n="nav.lang.<?php echo $lang; ?>"><?php echo $lang; ?></a></li>

								<?php endif; ?>

							<?php endforeach; ?>

						</ul>
				</li>

                <!-- TODO: check for noauth auth mechanism -->

                <?php if (!Str::contains(config('auth.methods'), 'NOAUTH')): ?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-user"></i> <?php echo \Auth::user()['email']; ?>
						<b class="caret"></b>
					</a>
					<ul class="dropdown-menu">
						<li>
                            <form id="logoutForm" action="<?php echo route('logout'); ?>" method="POST" style="display: none;">
                                <?php echo csrf_field(); ?>
                            </form>
							<a href="javascript:document.getElementById('logoutForm').submit()">
								<i class="fa fa-power-off"></i>
								<span data-i18n="nav.user.logout"></span>
							</a>
                            </form>
						</li>
					</ul>
				</li>
                <?php endif ?>

				<?php if(config('_munkireport.show_help')):?>
				
				<li>
						<a href="<?php echo config('_munkireport.help_url');?>" target="_blank">
								<i class="fa fa-question"></i>
						</a>
				</li>
				
				<?php endif; ?>

			</ul>

		</nav>
	</div>
</header>



	<?php /* endif; */ ?>
