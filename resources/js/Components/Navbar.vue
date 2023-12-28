<script lang="ts" setup>
  import { ref, defineProps, computed } from 'vue'
  import {Link, router, usePage} from '@inertiajs/vue3'
  import { useTranslation } from "i18next-vue";
  const { t, i18next } = useTranslation();

  import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'
  import {
    faPowerOff,
    faThLarge,
    faSearch,
    faUser,
    faQuestion
  } from '@fortawesome/free-solid-svg-icons'
  import {
    faChartBar,
    faListAlt
  } from '@fortawesome/free-regular-svg-icons'
  import Dropdown from "./Dropdown.vue";

  const page = usePage()
  const dashboards = computed(() => page.props.dashboards)
  const listings = computed(() => page.props.listings)
  const reports = computed(() => page.props.reports)
  const admin = computed(() => page.props.admin)
  const user = computed(() => page.props.user)

  let navbarOpen = ref(true)

  const logout = () => {
    router.post(route('logout'));
  };

  let appName = "MunkiReport 6.0"
</script>

<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-fixed-top">
    <a
      class="navbar-brand"
      href="/"
      v-text="appName"
    />
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

    <div
      id="navbarContent"
      class="collapse navbar-collapse"
    >
      <ul class="navbar-nav mr-auto">
        <!-- Dashboards -->
        <li
          v-if="dashboards.length < 2"
          class="nav-item"
        >
          <a
            class="nav-link"
            href="/"
          >
            <FontAwesomeIcon :icon="faThLarge" />
            {{ t('nav.main.dashboard') }}
          </a>
        </li>
        <li
          v-else
          class="nav-item dropdown"
        >
          <a
            class="nav-link dropdown-toggle"
            href="#"
            role="button"
            data-toggle="dropdown"
            aria-expanded="false"
          >
            <FontAwesomeIcon :icon="faThLarge" />
            {{ t('nav.main.dashboard_plural') }}
          </a>
          <div class="dashboard dropdown-menu">
            <a
              v-for="dashboard in dashboards"
              class="dropdown-item"
              :class="[dashboard.class]"
              :href="dashboard.url"
            >{{ dashboard.display_name }}</a>
          </div>
        </li>

        <!-- Reports -->
        <Dropdown>
          <template #toggle="toggleProps">
            <a
              id="reportsMenuLink"
              class="nav-link dropdown-toggle"
              href="#"
              role="button"
              data-toggle="dropdown"
              aria-expanded="false"
              @click="toggleProps.toggle"
            >
              <FontAwesomeIcon :icon="faChartBar" />
              {{ t('nav.main.reports') }}
            </a>
          </template>
          <a
            v-for="report in reports"
            class="dropdown-item"
            :class="[report.class]"
            :href="report.url"
          >{{ t(report.i18n) }}</a>
        </Dropdown>


        <!-- Listings -->
        <Dropdown>
          <template #toggle="toggleProps">
            <a
              id="listingsMenuLink"
              class="nav-link dropdown-toggle"
              href="#"
              role="button"
              data-toggle="dropdown"
              aria-expanded="false"
              @click="toggleProps.toggle"
            >
              <FontAwesomeIcon :icon="faListAlt" />
              {{ t('nav.main.listings') }}
            </a>
          </template>
          <a
            v-for="listing in listings"
            :href="listing.url"
            class="dropdown-item"
            :class="[listing.class]"
          >{{ $t(listing.i18n) }}</a>
        </Dropdown>

        <!-- Admin -->
        <Dropdown>
          <template #toggle="toggleProps">
            <a
              id="adminMenuLink"
              class="nav-link dropdown-toggle"
              href="#"
              role="button"
              data-toggle="dropdown"
              aria-expanded="false"
              @click="toggleProps.toggle"
            >
              <FontAwesomeIcon :icon="faListAlt" />
              {{ t('nav.main.admin') }}
            </a>
          </template>
          <a
            v-for="item in admin"
            :href="item.url"
            class="dropdown-item"
            :class="item.class"
          >{{ t(item.i18n) }}</a>
        </Dropdown>
      </ul>
    </div>

    <ul class="navbar-nav ml-auto">
      <!--      <li class="nav-item">-->
      <!--        <a href="#" id="filter-popup" class="nav-link filter-popup">-->
      <!--          -->
      <!--        </a>-->
      <!--      </li>-->

      <!--      <li class="nav-item dropdown">-->
      <!--        &lt;!&ndash; theme &ndash;&gt;-->
      <!--      </li>-->

      <!--      <li class="nav-item dropdown">-->
      <!--        &lt;!&ndash; locale &ndash;&gt;-->
      <!--      </li>-->


      <!-- User -->
      <Dropdown right>
        <template #toggle="toggleProps">
          <a
            id="userMenuLink"
            class="nav-link dropdown-toggle"
            href="#"
            role="button"
            data-toggle="dropdown"
            aria-expanded="false"
            @click="toggleProps.toggle"
          >
            <FontAwesomeIcon :icon="faUser" />
            {{ user.email }}
          </a>
        </template>

        <Link
          class="dropdown-item"
          href="/user/profile"
        >
          {{ t('nav.user.profile') }}
        </Link>
        <Link
          class="dropdown-item"
          href="/user/api-tokens"
        >
          {{ t('nav.user.tokens') }}
        </Link>
        <a
          class="dropdown-item"
          href="/api/documentation"
        >{{ t('nav.api.documentation') }}</a>
        <div class="dropdown-divider" />

        <form
          action="/auth/logout"
          method="POST"
          @submit.prevent="logout"
        >
          <button
            type="submit"
            class="dropdown-item"
          >
            <FontAwesomeIcon :icon="faPowerOff" />
            <span>{{ t("nav.user.logout") }}</span>
          </button>
        </form>
      </Dropdown>

      <li class="nav-item">
        <a
          class="nav-link"
          href="https://github.com/munkireport/munkireport-php/wiki"
          target="_blank"
        >
          <FontAwesomeIcon :icon="faQuestion" />
        </a>
      </li>
    </ul>
  </nav>
</template>


