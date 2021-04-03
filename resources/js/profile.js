require('./bootstrap');

window.Vue = require('vue');
import * as uiv from 'uiv';

Vue.use(uiv);

Vue.component('profile', require('./views/Profile.vue').default);

const page = new Vue({
  el: '#page'
});

