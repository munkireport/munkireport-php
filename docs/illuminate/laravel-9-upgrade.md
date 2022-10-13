# Laravel 9 Upgrade Process #

https://laravel.com/docs/9.x/upgrade

- Core dependencies updated
- Compatibility hacks made to public/index.php to support index.php? rewrites broke during the upgrade.
    - Fix was specified in "Trusted Proxies" section of upgrade guide.
- Comparison against `laravel new skeleton` Laravel 9 skeleton to make sure our modifications arent forward incompatible:
  - Reconcile `App/` templates such as AuthProvider and RouteProvider: mostly style differences or type hints
  - Reconcile `public/` just small syntax items.
  - Reconcile `routes/` very minor.
  - Database factories moved to `fake()` helper instead of faker method
- Migrate webpack to Vite via https://github.com/laravel/vite-plugin/blob/main/UPGRADE.md#migrating-from-laravel-mix-to-vite
  - bootstrap-vue only supports Vue2 but Laravel requires Vue3
    - there are some hacky ports, but they require Bootstrap v5.x
  - vue-template-compiler and vue-loader no longer required (part of webpack)
  - vue-apollo supports VueJS3 in v4 see https://v4.apollo.vuejs.org/
  -     "vue-cli-plugin-apollo": "~0.22.2", doesnt support Vue CLI 3 yet
  - vue-grid-layout does not seem to support Vue3
  - @panter/vue-i18next seems to be dead
  - sass-loader no longer needed, but keep sass dependency
  - choose `vite-plugin-simple-gql` over the more complex gql tag because this is most compatible with our source repo
  - vue-typeahead-bootstrap is dead
  - add vite-plugin-static-copy to address mix.copy missing
  - https://github.com/intlify/bundle-tools/tree/main/packages/vite-plugin-vue-i18n was the only i18n for vue3 + vite
  - vue-cli is dead in vite eg. https://vueschool.io/articles/vuejs-tutorials/how-to-migrate-from-vue-cli-to-vite/
  - vite defaults to serving JS over HTTP which makes the browser complain abotu mixed media. added vite plugin to generate self-signed cert in dev.
- VueJS 2 to 3 Migration
  - https://v3-migration.vuejs.org/ need to bring vue-router up too
    - router migration https://router.vuejs.org/guide/migration/index.html
    - 
