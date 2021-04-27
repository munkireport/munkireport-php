<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <a class="navbar-brand" :href="root" v-text="appName"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse bs-navbar-collapse" id="navbarSupportedContent">
      <div class="navbar-nav mr-auto">
        <li class="nav-item dropdown" :class="{ 'active': false }">
          <a class="nav-link dropdown-toggle" href="#" role="button" id="dashboardsMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-th-large"></i>
            <span>{{ $t("nav.main.dashboard_plural") }}</span>
            <b class="caret"></b>
          </a>
          <div class="dashboard dropdown-menu" aria-labelledby="dashboardsMenuLink">
            <a v-for="dashboard in dashboards" class="dropdown-item" :class="[dashboard.class]" :href="dashboard.url">
              <span class="pull-right" v-text="dashboard.hotkey"></span>
              <span class="dropdown-link-text" v-text="dashboard.display_name"></span>
            </a>
          </div>
        </li>

        <li class="nav-item dropdown" :class="{ 'active': false }">
          <a class="nav-link dropdown-toggle" href="#" role="button" id="reportsMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-bar-chart-o"></i>
            <span>{{ $t("nav.main.reports") }}</span>
            <b class="caret"></b>
          </a>
          <div class="report dropdown-menu" aria-labelledby="dashboardsMenuLink">
            <a v-for="report in reports" class="dropdown-item" :class="[report.class]" :href="report.url" :data-i18n="report.i18n"></a>
          </div>
        </li>

        <li class="nav-item dropdown" :class="{ 'active': false }">
          <a class="nav-link dropdown-toggle" href="#" role="button" id="listingMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-list-alt"></i>
            <span>{{ $t("nav.main.listings") }}</span>
            <b class="caret"></b>
          </a>
          <div class="listing dropdown-menu" aria-labelledby="listingMenuLink">
            <a v-for="listing in listings" class="dropdown-item" :class="[listing.class]" :href="listing.url" :data-i18n="listing.i18n"></a>
          </div>
        </li>

        <!-- if admin -->
        <li class="nav-item dropdown" :class="{ 'active': false }">
        <a class="nav-link dropdown-toggle" href="#" role="button" id="adminMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fa fa-list-alt"></i>
          <span>{{ $t("nav.main.admin") }}</span>
          <b class="caret"></b>
        </a>
        <div class="admin dropdown-menu" aria-labelledby="adminMenuLink">
          <a v-for="adminItem in adminItems" class="dropdown-item" :class="[adminItem.class]" :href="adminItem.url" :data-i18n="adminItem.i18n"></a>
        </div>
        </li>
        <!-- endif admin -->
      </div><!-- div navbar-nav mr-auto (left aligned) -->

      <!-- navbar-right -->
      <div class="navbar-nav ml-auto">
        <li class="nav-item">
          <a href="#" id="filter-popup" class="nav-link filter-popup">
            <i class="fa fa-filter"></i>
          </a>
        </li>

        <li class="dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" id="themeMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-wrench"></i>
          </a>
          <div class="theme dropdown-menu" aria-labelledby="themeMenuLink">
            <a v-for="theme in themes" class="dropdown-item" :data-switch="theme" href="#" v-text="theme"></a>
          </div>
        </li>

        <li class="dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" id="localeMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-globe"></i>
          </a>
          <div class="locale dropdown-menu" aria-labelledby="localeMenuLink">
            <a v-for="locale in locales" class="dropdown-item" :href="computedLocaleUrl" :data-i18n="'nav.lang.' + locale" v-text="locale"></a>
          </div>
        </li>

        <li class="dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" id="userMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-user"></i> {{ email }}
            <b class="caret"></b>
          </a>

          <div class="dropdown-menu" aria-labelledby="userMenuLink">
            <a class="dropdown-item" href="/me/tokens" data-i18n="nav.user.tokens">My API Tokens</a>
            <div class="dropdown-divider"></div>

            <form action="/auth/logout" method="POST">
              <button type="submit" class="dropdown-item">
                <i class="fa fa-power-off"></i>
                <span>{{ $t("nav.user.logout") }}</span>
              </button>
            </form>
          </div>
        </li>

        <li class="nav-item">
          <a class="nav-link" :href="helpUrl" target="_blank">
            <i class="fa fa-question"></i>
          </a>
        </li>
      </div><!-- div navbar-nav ml-auto (right aligned) -->
    </div><!-- navbar-collapse -->
  </nav>
</template>

<script>
export default {
  name: "Navigation",
  data() {
    return {
      root: "/",
      appName: "MunkiReport 6.0",

      dashboards: [],
      listings: [],
      reports: [],
      adminItems: [],

      themes: [],
      locales: [],

      email: "example@example.com",
      helpUrl: "",
    }
  }
}
</script>

<style scoped>

</style>
