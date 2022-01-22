require('./bootstrap');

import Vue from 'vue';
import i18next from 'i18next';
import Fetch from 'i18next-fetch-backend';
import VueI18Next from '@panter/vue-i18next';
import VueRouter from 'vue-router';
import VueApollo from 'vue-apollo';
import { ApolloClient } from 'apollo-client';
import { createHttpLink } from 'apollo-link-http';
import { InMemoryCache } from 'apollo-cache-inmemory';

import routes from './routes';

const httpLink = createHttpLink({
  // You should use an absolute URL here
  uri: '/graphql',
  headers: {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
  }
});

const cache = new InMemoryCache();

const apolloClient = new ApolloClient({
  link: httpLink,
  cache,
});

Vue.use(VueI18Next);
Vue.use(VueApollo);
Vue.use(VueRouter);

const apolloProvider = new VueApollo({
  defaultClient: apolloClient,
});

const I18nOptions = {
  debug: true, // mr.debug
  fallbackLng: 'en',
  ns: ['translation', 'event'],
  backend: {
    loadPath: '/locales/{{lng}}/{{ns}}.json',
    allowMultiLoading: false,
  }
};

i18next
  .use(Fetch)
  .init(I18nOptions);

const i18n = new VueI18Next(i18next);


const router = new VueRouter({
    routes
})

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('App', require('./components/App.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router,
    i18n,
    apolloProvider,
});
