<script lang="ts" setup>
  import { ref, defineProps, computed } from 'vue'
  import { Link, usePage } from '@inertiajs/vue3'
  import { useTranslation } from "i18next-vue";
  const { t, i18next } = useTranslation();

  import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'
  import {
    faPowerOff,
    faThLarge,
    faSearch,
    faUser,
  } from '@fortawesome/free-solid-svg-icons'
  import {
    faChartBar,
    faListAlt,
  } from '@fortawesome/free-regular-svg-icons'

  const page = usePage()
  const dashboards = computed(() => page.props.dashboards)
  const listings = computed(() => page.props.listings)
  const reports = computed(() => page.props.reports)
  const admin = computed(() => page.props.admin)
  const user = computed(() => page.props.user)

  let navbarOpen = ref(true)

  let appName = "MunkiReport"
</script>

<template>
  <nav class="navbar navbar-expand-lg navbar-light navbar-fixed-top">
    <Link class="navbar-brand" href="/" v-text="appName" />
    <button
        class="navbar-toggler"
        type="button"
        data-toggle="collapse"
        data-target="#navbarContent"
        aria-controls="navbarContent"
        aria-expanded="false"
        aria-label="Toggle navigation"
    >
      <span class="navbar-toggler-icon" />
    </button>

    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav mr-auto">

        <!-- Dashboards -->
        <li v-if="dashboards.length < 2" class="nav-item">
          <a class="nav-link" href="/">
            <FontAwesomeIcon :icon="faThLarge" />
            {{ t('nav.main.dashboard') }}
          </a>
        </li>
        <li v-else class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
            <FontAwesomeIcon :icon="faThLarge" />
            {{ t('nav.main.dashboard_plural') }}
          </a>
          <div class="dashboard dropdown-menu">
            <a v-for="dashboard in dashboards"
                  class="dropdown-item" :class="[dashboard.class]"
                  :href="dashboard.url"
            >{{ dashboard.display_name }}</a>
          </div>
        </li>


        <!-- Reports -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" id="reportsMenuLink" data-toggle="dropdown" aria-expanded="false">
            <FontAwesomeIcon :icon="faChartBar" />
            {{ t('nav.main.reports')}}
          </a>
          <div class="dropdown-menu">
            <a
                v-for="report in reports"
                class="dropdown-item"
                :class="[report.class]"
                :href="report.url"
            >{{ t(report.i18n) }}</a>
          </div>
        </li>


        <!-- Listings -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
            <FontAwesomeIcon :icon="faListAlt" />
            {{ t('nav.main.listings')}}
          </a>
          <div class="dropdown-menu">
            <a
                v-for="listing in listings"
                :href="listing.url"
                class="dropdown-item"
                :class="[listing.class]"
            >{{ $t(listing.i18n) }}</a>
          </div>
        </li>

        <!-- Admin -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
            <FontAwesomeIcon :icon="faListAlt" />
            {{ t('nav.main.admin')}}
          </a>
          <div class="dropdown-menu">
            <a
                v-for="item in admin"
                :href="item.url"
                class="dropdown-item"
                :className="item.class"
            >{{ t(item.i18n) }}</a>
          </div>
        </li>
      </ul>
    </div>

    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a href="#" id="filter-popup" class="nav-link filter-popup">
          filter
        </a>
      </li>

      <li class="nav-item dropdown">
        <!-- theme -->
      </li>

      <li class="nav-item dropdown">
        <!-- locale -->
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
          <FontAwesomeIcon :icon="faUser" />
          {{ user.email }}
        </a>
        <div class="dropdown-menu">
          <Link class="dropdown-item" href="/user/profile">{{ t('nav.user.profile')}}</Link>
          <a class="dropdown-item" href="/api/documentation">{{ t('nav.api.documentation')}}</a>
          <a class="dropdown-item dropdown-divider"></a>

          <form action="/auth/logout" method="POST">
            <button type="submit" class="dropdown-item">
              <FontAwesomeIcon :icon="faPowerOff" />
              <span>{{ t("nav.user.logout") }}</span>
            </button>
          </form>
        </div>
      </li>
    </ul>
  </nav>
</template>


