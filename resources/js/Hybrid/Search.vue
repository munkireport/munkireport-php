<script lang="ts" setup>
import {ref, defineProps, computed} from 'vue'
import type { Ref } from 'vue'
import { useDebounceFn } from '@vueuse/core'
import { useFetch } from '@vueuse/core'
import {useTranslation} from 'i18next-vue'
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'
import {
  faSearch,
  faTimesCircle
} from '@fortawesome/free-solid-svg-icons'
// @ts-ignore
import ModelIcon from '@/Components/ModelIcon.vue'

const search: Ref<null | string> = ref(null);
const url = computed(() => `/api/v6/search/machine/${search.value}`)

const { t, i18next } = useTranslation();
const { isFetching, error, data, execute } = useFetch(url, { immediate: false }).get().json()


const debounceMs = 400;
const onSearch = useDebounceFn(() => {
  execute()
}, debounceMs)
const hasSearch = computed(() => search.value !== null && search.value != '')
const isEmptyResult = computed(() => data.value === undefined || data.value === null || data.value.length === 0)

const onReset = (e) => {
  search.value = "";
}

</script>

<template>
  <form class="form-inline my-2 my-lg-0">
    <div class="dropdown">
      <div class="input-group">
        <input
          v-model="search"
          :aria-label="t('search.search')"
          :placeholder="t('search.search')"
          class="form-control"
          type="search"
          @keyup.prevent="onSearch"
        >
        <div class="input-group-append">
          <button
            id="reset-search"
            class="btn btn-outline-secondary"
            type="button"
            @click.prevent="onReset"
          >
            <FontAwesomeIcon
              v-if="hasSearch"
              :icon="faTimesCircle"
            />
            <FontAwesomeIcon
              v-else
              :icon="faSearch"
            />
          </button>
        </div>
      </div>
      <div
        class="dropdown-menu"
        :class="{ 'show': hasSearch }"
      >
        <a
          v-if="isFetching"
          class="dropdown-item disabled"
        >{{ t('loading') }}</a>
        <a
          v-else-if="isEmptyResult"
          class="dropdown-item disabled"
        >
          {{ t('search.noresults') }}
        </a>
        <a
          v-for="result in data"
          v-else
          :key="result.id"
          class="dropdown-item"
          :href="`/clients/detail/${result.serial_number}`"
        >
          <div class="d-flex">
            <div class="mr-4 d-flex align-items-center">
              <ModelIcon :model="result.machine_model" />
            </div>
            <div>
              <div v-text="result.hostname" />
              <div
                class="small quiet text-muted"
                v-text="result.serial_number"
              />
            </div>
          </div>

        </a>
      </div>
    </div>
  </form>
</template>

