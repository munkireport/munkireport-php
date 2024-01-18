<script lang="ts" setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { GridLayout, GridItem } from '@derpierre65/vue3-dragable-grid-layout'
import '@derpierre65/vue3-dragable-grid-layout/dist/style.css'

import demoLayout from '@/Components/Dashboard/demo-layout.ts'
import {reactive} from "vue";
// import ButtonWidget from '@/Components/Dashboard/Widgets/ButtonWidget.vue';
import DiskReportWidget from '@/Components/Dashboard/Widgets/DiskReportWidget.vue';
// import ScrollBoxWidget from '../components/widgets/ScrollBoxWidget.vue';
import EventsWidget from '@/Components/Dashboard/Widgets/EventsWidget.vue';

const layout = reactive(demoLayout);

const widgets = {
  'DiskReportWidget': DiskReportWidget,
  'EventsWidget': EventsWidget,
}

</script>

<template>
    <AppLayout title="Dashboard">
      <div class="container-fluid">
        <grid-layout
          v-model:layout="layout"
          :col-num="12"
          :row-height="30"
          :is-draggable="true"
          :is-resizable="true"
          :is-mirrored="false"
          :vertical-compact="true"
          :use-css-transforms="true"
          :responsive="true"
        >
          <template #default="{ gridItemProps }">
            <grid-item
               v-for="item in layout"
               :key="item.i"
               v-bind="gridItemProps"
               :x="item.x"
               :y="item.y"
               :w="item.w"
               :h="item.h"
               :i="item.i"
            >
              <component v-if="item.widget in widgets" :is="widgets[item.widget]" />
            </grid-item>
          </template>
        </grid-layout>
      </div>
    </AppLayout>
</template>
