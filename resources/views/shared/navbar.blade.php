<?php $page = $GLOBALS[ 'engine' ]->get_uri_string(); ?>
<header class="navbar navbar-default navbar-static-top bs-docs-nav" role="banner">
    <div class="container">
        <div class="navbar-header">
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo url(''); ?>">{{ $sitename }}</a>
        </div>
        <nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
            <ul class="nav navbar-nav">

                <li <?php echo $page==''?'class="active"':''; ?>>
                    <a href="<?php echo url(); ?>">
                        <i class="fa fa-th-large"></i>
                        <span class="visible-lg-inline" data-i18n="nav.main.dashboard"></span>
                    </a>
                </li>

                <?php $url = 'show/reports/'; ?>
                <li class="dropdown<?php echo strpos($page, $url)===0?' active':''; ?>">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bar-chart-o"></i>
                        <span data-i18n="nav.main.reports"></span>
                        <b class="caret"></b>
                    </a>
                    <ul class="report dropdown-menu">

                        <?php foreach($modules->getDropdownData('reports', 'show/report', $page) as $item): ?>

                        <li class="{{ $item->class }}">
                            <a href="{{ $item->url }}" data-i18n="{{ $item->i18n }}"></a>
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

                            <li class="{{ $item->class }}">
                                <a href="{{ $item->url }}" data-i18n="{{ $item->i18n }}"></a>
                            </li>

                        <?php endforeach; ?>

                    </ul>

                </li>

                @if ($role == 'admin')
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
                            <a href="<?php echo url($url.strtok($list_url, '.')); ?>" data-i18n="nav.admin.<?php echo $name = strtok($list_url, '.'); ?>"></a>
                        </li>

                        <?php endif; ?>

                        <?php endforeach; ?>

                    </ul>

                </li>
                @endif

                <li>
                    <a href="#" class="filter-popup">
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
                        @foreach ($themes as $theme)
                            <li><a data-switch="{{ $theme }}" href="#">{{ $theme }}</a></li>
                        @endforeach
                    </ul>
                </li>


                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-globe"></i>
                    </a>
                    <ul class="dropdown-menu locale">
                        @foreach ($locales as $locale)
                            <?php $lang = strtok($locale, '.'); ?>
                            <li><a href="<?php echo url($page, false, ['setLng' => $lang]); ?>" data-i18n="nav.lang.{{ $lang }}">{{ $lang }}</a></li>
                        @endforeach
                    </ul>
                </li>

                <?php if( ! array_key_exists('auth_noauth', conf('auth'))): // Hide logout button if auth_noauth?>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-user"></i> {{ $user }}
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="<?php echo url('auth/logout'); ?>">
                                <i class="fa fa-power-off"></i>
                                <span data-i18n="nav.user.logout"></span>
                            </a>
                        </li>
                    </ul>
                </li>

                <?php endif; ?>

            </ul>

        </nav>
    </div>
</header>
