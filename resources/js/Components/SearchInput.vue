<script lang="ts" setup>
import {ref, defineProps, defineModel} from 'vue'
import { useDebounceFn } from '@vueuse/core'
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'
import {
  faSearch
} from '@fortawesome/free-solid-svg-icons'

export interface Props {
  placeholder?: string;

  searchOnKeyup?: boolean;
  keyupDebounce?: number;
  searchOnClick?: boolean;

  loading?: boolean;
}

export interface Events {
  (event: 'search', value: string): void
}

const props = withDefaults(defineProps<Props>(), {
  placeholder: 'Search',
  searchOnKeyup: true,
  searchOnClick: true,
  keyupDebounce: 400,
  loading: false,
})

const search = defineModel<string>()
const emit = defineEmits<Events>()

const onSearch = useDebounceFn(() => {
  emit("search", search.value)
}, props.keyupDebounce)

</script>

<template>
  <div class="input-group">
    <input
      v-model="search"
      type="text"
      class="form-control"
      :placeholder="placeholder"
      aria-label="Hostname"
      @keyup.prevent="onSearch"
    >
    <div class="input-group-append">
      <span
        id="basic-addon1"
        class="input-group-text"
      >
        <FontAwesomeIcon
          :icon="faSearch"
          alt="Search"
        />
      </span>
    </div>
  </div>
</template>

