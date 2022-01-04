/**
 * This file provides an entry point for Vue.js components that exist in pages that are almost completely rendered by
 * Bootstrap + jQuery. It does not use Vue Router or attach itself to any root-level element. It should be safe to
 * include on a bootstrap/jquery page.
 */
const Search = require('./components/Search.vue').default;

Vue.component('mr-search', Search);
