import { createInertiaApp } from '@inertiajs/svelte'
import AppLayout from "./Layouts/AppLayout.svelte";

const resolve = (name) => {
  const pages = import.meta.glob('./Pages/**/*.svelte', { eager: true })
  let page = pages[`./Pages/${name}.svelte`]
  return { default: page.default, layout: page.layout || AppLayout }
}

createInertiaApp({
  resolve,
  setup({ el, App, props }) {
    new App({ target: el, props })
  },
})
