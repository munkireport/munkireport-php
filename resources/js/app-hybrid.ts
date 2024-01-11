import {createApp, h} from 'vue'
import type {DefineComponent} from 'vue'
import { ZiggyVue } from 'ziggy-js/dist/vue.es.js'
import i18next from 'i18next'
import I18NextVue from 'i18next-vue'
import Fetch from 'i18next-fetch-backend';
import urql, { cacheExchange, fetchExchange } from '@urql/vue';

i18next
    .use(Fetch)
    .init({
        lng: 'en',
        fallbackLng: 'en',
        ns: ['translation', 'event'],
        fallbackNS: ['event'],
        backend: {
            loadPath: '/locales/{{lng}}/{{ns}}.json',
            allowMultiLoading: false,
        },
        // interpolation: {
        //   // To satisfy i18n interpolation used in MunkiReport v5
        //   prefix: '__',
        //   suffix: '__',
        // }
    })

const createHybridApp = (options) => {
    const app = createApp(options)
        .use(ZiggyVue)
        .use(I18NextVue, {i18next})
        .use(urql, {
            url: '/graphql',
            exchanges: [cacheExchange, fetchExchange],
            fetchOptions: () => {
                const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]')
                if (!csrfTokenMeta) {
                    console.log('No CSRF token was found on the current view, this should be an error');
                    return {}
                } else {
                    const csrfToken = (<HTMLMetaElement>csrfTokenMeta).content
                    return {
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    }
                }

            }
        })
    return app
}

// @ts-ignore
import Search from './Hybrid/Search.vue'
createHybridApp(Search).mount('#search')
