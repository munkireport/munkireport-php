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

