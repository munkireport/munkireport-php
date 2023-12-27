import i18next from "i18next";
import type {InitOptions} from "i18next";
import { createI18nStore } from "svelte-i18next";
import Fetch from 'i18next-fetch-backend';

const options: InitOptions = {
  lng: 'en',
  fallbackLng: 'en',
  ns: ['translation', 'event'],
  fallbackNS: 'event',
  backend: {
    loadPath: '/locales/__lng__/__ns__.json',
    allowMultiLoading: false,
  },
  interpolation: {
    escapeValue: false, // not needed for svelte as it escapes by default
    // To satisfy i18n interpolation used in MunkiReport v5
    prefix: '__',
    suffix: '__',
  }
}

i18next.use(Fetch).init(options);

export const i18n = createI18nStore(i18next);
