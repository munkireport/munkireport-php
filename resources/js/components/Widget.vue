<template>
  <div class="widget card h-100 shadow-sm">
    <slot name="header">
      <div class="card-header" :title="$t(i18nTitle)">
        <i v-if="icon" class="fa" :class="[icon]"></i>
        <span v-wait-for-t>{{ $t(i18nTitle) }}</span>
        <a v-if="link" :href="link" class="pull-right text-reset"><i class="fa fa-list"></i></a>
      </div>
    </slot>
    <slot></slot>
    <slot name="footer">

    </slot>
  </div>
</template>

<script>
export default {
  name: "Widget",

  props: {
    // Widget has an error, such as a data fetch failure, display the "error" slot.
    error: {
      type: Boolean,
      default: false,
    },

    // Widget is loading data
    loading: {
      type: Boolean,
      default: false,
    },

    // Widget can be changed and moved
    editing: {
      type: Boolean,
      default: false,
    },

    // The i18n key to translate which is used for the header.
    i18nTitle: String,

    // A class name of a font-awesome icon to use for the corner of the widget.
    icon: {
      type: String,
      required: false,
    },

    // The link (usually a listing) to more/deeper detail
    link: {
      type: String,
      required: false,
    }
  }
}
</script>

<style scoped>

</style>
