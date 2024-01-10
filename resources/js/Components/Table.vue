<script lang="ts" setup>
import {ref, defineProps, defineModel} from 'vue'
import {Link, usePage} from '@inertiajs/vue3'
import {useTranslation} from 'i18next-vue'
import {useSelection} from "../Composables/useSelection";

import {useSortableHeaders} from "../Composables/useSortableHeaders";
import SortIndicator from "@/Components/Table/SortIndicator.vue"

export interface Column {
  key: string;
  label: string;
  sortable?: boolean;
  sort?: "ASC" | "DESC";
}

export interface Props<TRecord> {
  columns: Column[];
  data: Iterable<TRecord>;

  dark?: boolean;
  striped?: boolean;
  bordered?: boolean;
  borderless?: boolean;
  hover?: boolean;
  sm?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  dark: false,
  striped: false,
  bordered: false,
  borderless: false,
  hover: false,
  sm: false,
});

const { selection, toggleSelect } = useSelection();
const { headers, toggleSort } = useSortableHeaders(props.columns);

</script>

<template>
  <table
    class="table"
    :class="{ 'table-dark': props.dark, 'table-striped': props.striped, 'table-bordered': props.bordered, 'table-borderless': props.borderless, 'table-hover': props.hover, 'table-sm': props.sm }"
  >
    <thead>
      <slot name="head">
        <th />
        <th
          v-for="header in headers"
          :key="header.key"
          :class="{ 'sortable': header.sortable }"
          scope="col"
          @click.prevent="header.sortable && toggleSort(header.key)"
        >
          {{ header.label }}

          <SortIndicator
            v-if="header.sortable"
            :sort="header.sort"
            show-unsorted-indicator
          />
        </th>
      </slot>
    </thead>
    <tbody>
      <slot name="body">
        <tr v-if="props.data.length === 0">
          <td :colspan="props.columns.length+1">
            No results available
          </td>
        </tr>
        <tr
          v-for="datum in props.data"
          :key="datum[props.columns[0].key] + '-row'"
          :class="{ 'row-selected': selection.includes(datum[props.columns[0].key]) }"
          class="row-selectable"
          @click.prevent="toggleSelect(datum[props.columns[0].key])"
        >
          <td class="flex justify-content-center">
            <div class="form-check">
              <input
                :id="datum[props.columns[0].key] + '-row-select'"
                class="form-check-input"
                type="checkbox"
                :value="datum[props.columns[0].key]"
                :checked="selection.includes(datum[props.columns[0].key])"
              >
            </div>
          </td>
          <td
            v-for="column in props.columns"
            :key="datum[column.key]"
          >
            <slot
              :item="datum"
              :column="column"
            >
              {{ datum[column.key] }}
            </slot>
          </td>
        </tr>
      </slot>
    </tbody>
    <tfoot>
      <slot name="foot">
        <th :colspan="columns.length + 1">
          <span class="quiet">{{ selection.length }} item<span v-if="selection.length !== 1">s</span> selected</span>
        </th>
      </slot>
    </tfoot>
  </table>
</template>

<style>
  .row-selected {

  }

  th.sortable {
    cursor: pointer;
  }

  .row-selectable {
    cursor: pointer;
  }
</style>
