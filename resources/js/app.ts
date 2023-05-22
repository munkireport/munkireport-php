import { createInertiaApp } from '@inertiajs/svelte'

const resolve = (name) => {
  const pages = import.meta.glob('./Pages/**/*.svelte', { eager: true })
  return pages[`./Pages/${name}.svelte`]
}

createInertiaApp({
  resolve,
  setup({ el, App, props }) {
    new App({ target: el, props })
  },
})
