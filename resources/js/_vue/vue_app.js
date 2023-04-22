import {createApp} from 'vue'
import {createRouter, createWebHistory} from 'vue-router'

import {createApolloProvider} from '@vue/apollo-option'
import {ApolloClient} from 'apollo-client';
import {createHttpLink} from 'apollo-link-http';
import {InMemoryCache} from 'apollo-cache-inmemory';

import {loadLocaleMessages, setI18nLanguage, setupI18n, SUPPORT_LOCALES} from './i18n';

import 'bootstrap/dist/css/bootstrap.css'
import '../sass/app.scss'

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
const apolloProvider = createApolloProvider({
  defaultClient: apolloClient,
});

const i18n = setupI18n({});

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach(async (to, from, next) => {
  const paramsLocale = 'en'; //to.params.locale

  // use locale if paramsLocale is not in SUPPORT_LOCALES
  if (!SUPPORT_LOCALES.includes(paramsLocale)) {
    return next(`/${locale}`)
  }

  // load locale messages
  if (!i18n.global.availableLocales.includes(paramsLocale)) {
    await loadLocaleMessages(i18n, paramsLocale)
  }

  // set i18n language
  setI18nLanguage(i18n, paramsLocale)

  return next()
})

import App from './components/App.vue';
const app = createApp(App);

app.use(i18n);
app.use(apolloProvider);
app.use(router);

app.mount('#app');
