require('./bootstrap');
import { ApolloClient } from 'apollo-client';
import { createHttpLink } from 'apollo-link-http';
import { InMemoryCache } from 'apollo-cache-inmemory';

window.Vue = require('vue');
import VueApollo from 'vue-apollo';

Vue.use(VueApollo);

const httpLink = createHttpLink({
  uri: 'https://elhack.local/graphql',
});
const cache = new InMemoryCache();
const apolloClient = new ApolloClient({
  link: httpLink,
  cache,
});


Vue.component('business-units', require('./views/BusinessUnits.vue').default);

const apolloProvider = new VueApollo({
  defaultClient: apolloClient,
});

const page = new Vue({
  el: '#page',
  apolloProvider,
});

