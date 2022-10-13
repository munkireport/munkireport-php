import './bootstrap';

import Vue from 'vue';
window.Vue = Vue;
import * as uiv from 'uiv';
import Profile from './views/Profile.vue';

Vue.use(uiv);

Vue.component('profile', Profile);

const page = new Vue({
  el: '#page'
});

