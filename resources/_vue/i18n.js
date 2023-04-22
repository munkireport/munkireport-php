import { nextTick } from 'vue'
import { createI18n } from 'vue-i18n'
// import i18next from 'i18next';
// import Fetch from 'i18next-fetch-backend';

export const SUPPORT_LOCALES = ['en', 'de', 'fr']

export function setupI18n(options = { locale: 'en', fallbackLocale: 'en' }) {
  const i18n = createI18n(options)
  setI18nLanguage(i18n, options.locale)
  return i18n
}

export function setI18nLanguage(i18n, locale) {
  if (i18n.mode === 'legacy') {
    i18n.global.locale = locale
  } else {
    i18n.global.locale.value = locale
  }
  /**
   * NOTE:
   * If you need to specify the language setting for headers, such as the `fetch` API, set it here.
   * The following is an example for axios.
   *
   * axios.defaults.headers.common['Accept-Language'] = locale
   */
  document.querySelector('html').setAttribute('lang', locale)
}


export async function loadLocaleMessages(i18n, locale, ns) {
  // load locale messages with dynamic import
  const messages = await import(
      /* webpackChunkName: "locale-[request]" */ `../../public/assets/locales/${locale}.json`)

  // set locale and locale message
  i18n.global.setLocaleMessage(locale, messages.default)

  return nextTick()
}

// Not supported by vue-i18n but preserved to indicate correct config
// const I18nOptions = {
//   debug: true, // mr.debug
//   fallbackLng: 'en',
//   ns: ['translation', 'event'],
//   backend: {
//     loadPath: '/locales/{{lng}}/{{ns}}.json',
//     allowMultiLoading: false,
//   },
//   interpolation: {
//     // To satisfy i18n interpolation used in MunkiReport v5
//     // prefix: '__',
//     // suffix: '__',
//   }
// };
//
// i18next
//   .use(Fetch)
//   .init(I18nOptions);
