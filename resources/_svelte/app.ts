import { createInertiaApp } from '@inertiajs/svelte'
import AppLayout from "./Layouts/AppLayout.svelte";

const resolve = (name) => {
  const namespaced = name.split('::')

  if (namespaced.length === 1) {
    const pages = import.meta.glob('./Pages/**/*.svelte', {eager: true})
    let page = pages[`./Pages/${name}.svelte`]
    return {default: page.default, layout: page.layout || AppLayout}
  }
  // } else {
  //   const [namespace, ...rest] = namespaced;
  //   const modulePages = import.meta.glob('@vendor/munkireport/**/resources/js/Pages/**/*.svelte', { eager: true })
  //   let page = modulePages[`@vendor/munkireport/${namespace}/resources/js/Pages/${rest.join('')}.svelte`];
  //   return page;
  // }
}

createInertiaApp({
  resolve,
  setup({ el, App, props }) {
    new App({ target: el, props })
  },
})
