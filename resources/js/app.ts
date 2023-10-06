import {createApp, h} from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m'
import { Ziggy } from './ziggy'
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

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        return pages[`./Pages/${name}.vue`]
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(I18NextVue, {i18next})
            .use(urql, {
                url: '/graphql',
                exchanges: [cacheExchange, fetchExchange],
                fetchOptions: () => {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').content
                    return {
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    }
                }
            })
            .mount(el)
    },
});

