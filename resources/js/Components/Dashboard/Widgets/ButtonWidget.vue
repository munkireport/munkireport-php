<script lang="ts" setup>
import {defineProps} from 'vue'

export interface WidgetThreshold {
  label: string;
  classes?: string;
  link?: string;
}

export interface Props {
  tooltip?: string;
  icon: string;
  title: string;
  thresholds: WidgetThreshold[];
  values: number[];
  listingUrl?: string;
}

const props = defineProps<Props>()

</script>

<template>
  <div class="card h-100">
    <div class="card-header" :title="tooltip">
      <i v-if="icon" class="fa" :class="[icon]"></i>
      <span>{{ $t(title) }}</span>
      <a v-if="listingUrl" :href="listingUrl" class="pull-right text-reset"><i class="fa fa-list"></i></a>
    </div>
    <div class="card-body d-flex align-items-stretch">
      <a v-for="(threshold, i) in thresholds"
         :key="threshold.label"
         :href="threshold.link"
         class="btn btn-success flex-fill mx-1 d-flex flex-column"
         :class="['threshold-' + i, threshold.classes]">

        <div v-if="values" class="flex-fill">
          <h3 class="align-middle" v-text="values[i]"></h3>
        </div>
        <div v-else class="flex-fill align-baseline">
          <h3>0</h3>
        </div>
        <div class="label" v-text="threshold.label"></div>
      </a>
    </div>
  </div>
</template>
